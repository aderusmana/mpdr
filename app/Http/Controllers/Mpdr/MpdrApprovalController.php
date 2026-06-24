<?php

namespace App\Http\Controllers\Mpdr;

use App\Http\Controllers\Controller;
use App\Jobs\Mpdr\ProcessApproval;
use App\Jobs\Mpdr\sendResultToUser;
use App\Jobs\Mpdr\sendUpdateToAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\MPDR\MpdrForm;
use App\Models\MPDR\MpdrApprover;
use App\Notifications\MpdrNotification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;

class MpdrApprovalController extends Controller
{
    public function approval()
    {
        return view('page.mpdr.approval-mpdr');
    }

    public function approver()
    {
        $users = User::role('approver')->get();
        confirmDelete();
        return view('page.mpdr.approver-mpdr');
    }
    public function viewApprovalForm($no_reg)
    {
        confirmDelete();
        return view('page.mpdr.form-approval-mpdr')->with('no_reg', $no_reg);
    }

    public function getApproverListData()
    {
        $approver = User::role('approver')->where('status', 'Active')->get();
        if($approver){
            return response()->json($approver);
        }

        return response()->json("Tidak ada data");
    }

    public function getSelectedApproverList()
    {
        $user_nik = Auth::user()->nik;
        $approverList = MpdrApprover::select('approver_nik', 'approver_name', 'approver_status')->where('user_nik', $user_nik)->get()->toArray();
        if($approverList){
            return response()->json($approverList);
        }
        return response()->json([]);
    }

    public function updateApproverOrder(Request $request)
    {
        // [PERBAIKAN 1] Validasi diubah untuk menerima satu array objek 'approvers'
        $validated = $request->validate([
            'approvers'         => 'present|array', // 'approvers' harus ada dan berupa array (boleh kosong)
            'approvers.*.nik'   => 'required|string',
            'approvers.*.name'  => 'required|string',
            'approvers.*.status'=> 'required|string',
        ]);

        $user_nik = Auth::user()->nik;
        $approvers_data = $validated['approvers']; // Ambil array approver yang sudah divalidasi

        DB::beginTransaction();
        try {
            // Hapus semua approver lama untuk user ini
            MpdrApprover::where('user_nik', $user_nik)->delete();

            // [PERBAIKAN 2] Loop melalui array objek 'approvers_data'
            if (!empty($approvers_data)) {
                foreach ($approvers_data as $approver) {
                    MpdrApprover::create([
                        'user_nik'        => $user_nik,
                        'approver_nik'    => $approver['nik'],
                        'approver_name'   => $approver['name'],
                        'approver_status' => $approver['status']
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Approver list has been saved successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while saving the approver list: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getApprovalListData()
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return response()->json([]); // Return empty array if no user is logged in
        }
        $nik = $user->nik;

        // [PERBAIKAN UTAMA] Query forms that are "In Approval" and meet one of the two conditions below.
        $forms = MpdrForm::where('status', 'In Approval')
            ->where(function ($query) use ($nik) {
                // Condition 1: The user is the INITIATOR and their turn is pending.
                $query->whereHas('initiatorDetail', function ($q) use ($nik) {
                    $q->where('initiator_nik', $nik)->where('status', 'pending');
                })
                // OR
                // Condition 2: The user is a standard APPROVER and their turn is pending.
                ->orWhereHas('approvedDetail', function ($q) use ($nik) {
                    $q->where('approver_nik', $nik)->where('status', 'pending');
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($forms);
    }

    public function approveForm(Request $request, $no_reg)
    {
            // (Validator dipindahkan ke use statement di atas)
        $validator = Validator::make($request->all(), [
            'action' => 'required|string|in:approve,approve with review,not approve',
            'comment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        DB::beginTransaction();
        try {
            /** @var User $user */
            $user = Auth::user();
            $nik = $user->nik;

            // [LOGIKA BARU] Muat relasi 'user' (pembuat form) untuk notifikasi
            $form = MpdrForm::with(['initiatorDetail', 'approvedDetail', 'user'])
                ->where('no', $no_reg)
                ->firstOrFail();

            if ($form->status !== 'In Approval') {
                throw new \Exception("Form ini sudah tidak dalam status persetujuan.");
            }

            $action = $request->input('action');
            $comment = $request->input('comment');
            $processed = false;

            // Logika untuk Initiator
            if ($form->initiatorDetail->initiator_nik == $nik && $form->initiatorDetail->status == 'pending') {
                $form->initiatorDetail->update([
                    'status' => $action,
                    'comment' => ($action !== 'approve') ? $comment : null,
                    'approved_date' => now(),
                    'token' => null
                ]);
                $processed = true;
            }
            // Logika untuk Approver
            else {
                $detailToUpdate = $form->approvedDetail()->where('approver_nik', $nik)->where('status', 'pending')->first();
                if ($detailToUpdate) {
                    $detailToUpdate->update([
                        'status' => $action,
                        'comment' => ($action !== 'approve') ? $comment : null,
                        'approved_date' => now(),
                        'token' => null
                    ]);
                    $processed = true;
                    // Jika approver adalah GM, maka semua yang masih 'Vacant' atau 'pending'
                    // akan dianggap disetujui oleh GM (approve with review) atau ditolak sesuai aksi.
                    if ($user->hasRole('gm')) {
                        foreach ($form->approvedDetail as $detail) {
                            if ($detail->status === 'Vacant' || $detail->status === 'pending') {
                                $detail->approved_date = now();
                                if ($action === 'approve' || $action === 'approve with review') {
                                    $detail->status = 'approve with review';
                                    $detail->comment = 'Approved with GM';
                                } else if ($action === 'not approve') {
                                    $detail->status = 'not approve';
                                    $detail->comment = 'Not approved by GM';
                                }
                                $detail->token = null;
                                $detail->save();
                            }
                        }
                    }
                }
            }

            if (!$processed) {
                throw new \Exception("Bukan giliran Anda untuk menyetujui atau Anda sudah memproses form ini.");
            }

            // Cek status form utama (asumsi: fungsi ini mengubah $form->status menjadi 'Approved' atau 'Rejected' jika perlu)
            $this->checkFormInApprovalStatus($no_reg);

            // Muat ulang data form untuk mendapatkan status terbaru
            $form->refresh();

            // Ambil user pembuat form (requester) dari relasi yang sudah dimuat
            $requester = $form->user;

            // =================================================================
            // LOGIKA NOTIFIKASI BARU
            // Menggantikan sendMailToAdmin() dan sendMailToUser()
            // =================================================================

            if ($action === 'not approve') {
                // --- SKENARIO 1: DITOLAK ---
                // Kirim notifikasi HANYA ke pembuat form (requester)
                if ($requester) {
                    $rejectData = [
                        'title' => 'MPDR Ditolak',
                        'message' => 'Dokumen Anda (' . $form->no . ') telah ditolak oleh ' . $user->name . '.',
                        'form_no' => $form->no,
                    ];
                    $requester->notify(new MpdrNotification($rejectData, 'rejected'));
                }

            } else {
                // --- SKENARIO 2: DISETUJUI (approve / approve with review) ---

                // 2a. Broadcast notifikasi informasi ke SEMUA user (role 'user')
                // (Kecuali diri sendiri yang baru approve)
                $allUsers = User::role('user')->get()->except($user->id);
                if ($allUsers->isNotEmpty()) {
                    $broadcastData = [
                        'title' => 'Update Status MPDR',
                        'message' => 'Dokumen ' . $form->no . ' telah disetujui oleh ' . $user->name . '.',
                        'form_no' => $form->no,
                    ];
                    Notification::send($allUsers, new MpdrNotification($broadcastData, 'updates'));
                }

                // 2b. Cek status form SETELAH approval
                if ($form->status === 'In Approval') {
                    // --- Masih dalam proses, kirim ke APPROVER SELANJUTNYA ---

                    $nextApproverNik = null;
                    // Cek apakah initiator detail adalah yang selanjutnya
                    if ($form->initiatorDetail->status == 'pending') {
                        $nextApproverNik = $form->initiatorDetail->initiator_nik;
                    } else {
                        // Jika tidak, cari di approved detail
                        $nextDetail = $form->approvedDetail()->where('status', 'pending')->orderBy('created_at', 'asc')->first();
                        if($nextDetail) {
                            $nextApproverNik = $nextDetail->approver_nik;
                        }
                    }

                    if ($nextApproverNik) {
                        $nextApproverUser = User::where('nik', $nextApproverNik)->first();
                        if ($nextApproverUser) {
                            $taskData = [
                                'title' => 'Permintaan Persetujuan MPDR',
                                'message' => 'Dokumen ' . $form->no . ' membutuhkan persetujuan Anda.',
                                'form_no' => $form->no,
                            ];
                            $nextApproverUser->notify(new MpdrNotification($taskData, 'approval_request'));
                        }
                    }

                } else if ($form->status === 'Approved') {
                    // --- Sudah FINAL, kirim ke REQUESTER ---
                    if ($requester) {
                        $finalData = [
                            'title' => 'MPDR Telah Disetujui Penuh',
                            'message' => 'Dokumen Anda (' . $form->no . ') telah disetujui sepenuhnya.',
                            'form_no' => $form->no,
                        ];
                        $requester->notify(new MpdrNotification($finalData, 'approved'));
                    }
                }
            }
            // =================================================================
            // AKHIR DARI LOGIKA NOTIFIKASI
            // =================================================================

            // Jika approver adalah GM dan aksi bukan 'not approve' dan form tidak ditolak,
            // kirim update email/hasil ke admin dan ke semua user (org).
            if ($user->hasRole('gm') && $action !== 'not approve' && $form->status !== 'Rejected') {
                try {
                    $this->sendMailToAdmin($no_reg, $user);
                    $this->sendMailToUser($no_reg);
                } catch (\Exception $e) {
                    // Jangan ganggu alur utama jika pengiriman email gagal; log jika perlu.
                }
            }


            // Catat aktivitas (ini sudah benar)
            activity()->performedOn($form)->inLog('mpdr')->event(ucfirst($action))
                ->causedBy($user)->log(ucfirst($action) . ' Mpdr Form ' . $no_reg);

            DB::commit();

            // [PERBAIKAN 2] Kembalikan respons JSON, bukan redirect
            return response()->json([
                'success' => true,
                'message' => 'Form berhasil diproses!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            // [PERBAIKAN 3] Kembalikan error sebagai JSON
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    private function sendMailToAdmin($no_reg, $approver)
    {
        $form = MpdrForm::with('revision', 'detail', 'category', 'channel', 'description', 'certification', 'competitor', 'packaging', 'market', 'approvedDetail', 'initiatorDetail')
        ->where('no', $no_reg)->first();

        $admins = User::whereHas('roles', function($query) {
            $query->where('name', 'super-admin');
        })->get(); // Ambil semua super admin

        foreach($admins as $admin){ // Foreach semua admin
            sendUpdateToAdmin::dispatch($admin, $form, $approver)->delay(10);

            // kirim notif website
            $data = [
                'title' => 'MPDR ' . $form->no,
                'message' => 'Has been ' . ($form->status == 'Rejected' ? 'not approve' : 'approve'  ) . ' by ' . $approver->name . '.',
                'user_id' => $form->user_id,
            ];
            $notificationType = 'updates'; // Tipe notifikasi yang sesuai
            $admin->notify(new MpdrNotification($data, $notificationType));
        }
    }

    private function sendMailToApproversExceptGM($no_reg)
    {
        // Kirim form ke semua approver kecuali gm
        $form = MpdrForm::with('revision', 'detail', 'category', 'channel', 'description', 'certification', 'competitor', 'packaging', 'market', 'initiatorDetail', 'approvedDetail')
        ->where('no', $no_reg)->first();

        if($form->status == 'In Approval'){ // Cek jika status form masih In Approval
            foreach($form->approvedDetail as $detail){ // send email ke semua approver kecuali gm
                $user = User::where('nik', $detail->approver_nik)->first();
                if(!$user->hasRole('gm') && $detail->status == 'pending'){
                    // kirim email
                    ProcessApproval::dispatch($user, $form, $detail)->delay(10);

                    // kirim notif app
                    $data = [
                        'title' => 'New MPDR for Approval',
                        'message' => $form->no . ' needs your approval.',
                        'user_id' => $form->user_id,
                    ];
                    $notificationType = 'approval_request'; // Tipe notifikasi yang sesuai
                    $user->notify(new MpdrNotification($data, $notificationType));
                }
            }
        }
    }

    private function sendMailToUser($no_reg)
    {
        $form = MpdrForm::with('revision', 'detail', 'category', 'channel', 'description', 'certification', 'competitor', 'packaging', 'market', 'initiatorDetail', 'approvedDetail')
        ->where('no', $no_reg)->first();

        if($form->status == 'Approved') // Kirim ke semuanya jika form status Approved
        {
            $allApproverNik = $form->approvedDetail()->pluck('approver_nik'); // Semua nik approver yang dituju
            $allApprover = User::whereIn('nik', $allApproverNik)->get(); // Ambil usernya
            $allAdmin = User::whereHas('roles', function($query) {
                $query->where('name', 'super-admin');
            })->get(); // Semua super admin
            $allUser = $allApprover->merge($allAdmin)->unique('id');

            foreach($allUser as $user){ // Foreach semua user
                sendResultToUser::dispatch($user,  $form)->delay(10); // send email

                // kirim notif app
                $data = [
                    'title' => 'MPDR approved',
                    'message' => $form->no . ' has been approved.',
                    'user_id' => $form->user_id,
                ];
                $notificationType = 'approved'; // Tipe notifikasi yang sesuai
                $user->notify(new MpdrNotification($data, $notificationType));
            }

            activity()
                ->performedOn($form)
                ->inLog('mpdr')
                ->event('Final')
                ->withProperties(['no' => $form->no, 'action' => 'approved'])
                ->log($form->no . ' has been Approved at ' . now());
        }
        else if($form->status == 'Rejected') // Kirim ke initiator dan admin jika form status rejected
        {
            $initiator = User::where('nik', $form->initiatorDetail->initiator_nik)->first();
            $allAdmin = User::whereHas('roles', function($query) {
                $query->where('name', 'super-admin');
            })->get(); // Semua super admin
            $allUser = $allAdmin->push($initiator)->unique('id'); // Gabung initiator dan admin

            //foreach initiator dan admin
            foreach($allUser as $user){
                sendResultToUser::dispatch($user, $form)->delay(10); // send email

                // kirim notif app
                $data = [
                    'title' => 'MPDR rejected',
                    'message' => $form->no . ' has been rejected.',
                    'user_id' => $form->user_id,
                ];
                $notificationType = 'rejected'; // Tipe notifikasi yang sesuai
                $user->notify(new MpdrNotification($data, $notificationType));
            }

            activity()
                ->performedOn($form)
                ->inLog('mpdr')
                ->event('Final')
                ->withProperties(['no' => $form->no, 'action' => 'rejected'])
                ->log($form->no . ' has been Rejected at ' . now());

        }
        else if($form->status == 'In Approval') // Jika form in approval cek dan kirim ke gm
        {
            $this->sendMailToGM($no_reg);
        }
    }

    public function sendMailToGM($no_reg)
    {
        $form = MpdrForm::with('revision', 'detail', 'category', 'channel', 'description', 'certification', 'competitor', 'packaging', 'market', 'approvedDetail', 'initiatorDetail')
        ->where('no', $no_reg)->first();
        if($form->status == 'In Approval') // cek jika status masih In Approval
        {
            // cek apakah semua approver kecuali gm sudah approve
            $approveStatus = True;
            $gm = null;
            $gm_detail = null;
            foreach($form->approvedDetail as $detail){
                $user = User::where('nik', $detail->approver_nik)->first();
                if(!$user->hasRole('gm'))
                {
                    if($detail->status == 'not approve' || $detail->status == 'pending')
                    {
                        $approveStatus = False;
                        break;
                    }
                }
                else if ($user->hasRole('gm'))
                {
                    $gm = $user;
                    $gm_detail = $detail;
                    if($detail->status !== 'pending' && $detail->token == null ) // Cek jika gm sudah approve dan token null
                    {
                        $approveStatus = False;
                        break;
                    }
                }
            }

            if($approveStatus){
                ProcessApproval::dispatch($gm, $form, $gm_detail)->delay(10);

                // kirim notif app
                $data = [
                    'title' => 'New MPDR for Approval',
                    'message' => $form->no . ' needs your approval.',
                    'user_id' => $form->user_id,
                ];
                $notificationType = 'approval_request'; // Tipe notifikasi yang sesuai
                $gm->notify(new MpdrNotification($data, $notificationType));
            }
        }
    }

    public function sendMailToGMUrgent($no_reg)
    {
        try {
            $form = MpdrForm::with('approvedDetail.user')->where('no', $no_reg)->firstOrFail();

            if ($form->status !== 'In Approval') {
                return response()->json([
                    'success' => false,
                    'message' => 'Email can only be sent for forms that are "In Approval".'
                ], 400);
            }

            // 3. Loop untuk mencari approver dengan role 'gm'
            foreach ($form->approvedDetail as $detail) {
                $user = User::where('nik', $detail->approver_nik)->first();

                // Cek apakah user ditemukan dan memiliki role 'gm'
                if ($user && $user->hasRole('gm')) {

                    // 4. Cek apakah GM tersebut masih perlu melakukan approval
                    if ($detail->status === 'pending') {
                        // Jika masih pending, kirim email melalui background job
                        ProcessApproval::dispatch($user, $form, $detail)->delay(10);

                        // 5. Kembalikan respons JSON yang menandakan sukses
                        return response()->json([
                            'success' => true,
                            'message' => 'Urgent email has been successfully sent to the GM.'
                        ]);
                    } else {
                        // Jika GM sudah memproses form ini
                        return response()->json([
                            'success' => false,
                            'message' => 'The General Manager has already processed this form.'
                        ], 400);
                    }
                }
            }

            // Kembalikan respons JSON yang menandakan sukses
            return response()->json([
                'success' => true,
                'message' => 'Urgent email has been successfully sent to the GM.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    private function checkFormInApprovalStatus($no_reg)
    {
        $form = MpdrForm::with('approvedDetail')
        ->where('status', 'In Approval')
        ->where('no', $no_reg)->first();
        if($form){
            $approved = True;
            foreach($form->approvedDetail as $detail){
                if($detail->status === 'not approve'){
                    $form->status = 'Rejected';
                    $form->route_to = null;
                    $approved = False;
                    $form->save();
                    break;
                }else if($detail->status == 'pending'){
                    $approved = False;
                }
            }

            if($approved){
                $form->status = 'Approved';
                $form->save();
            }

            if($form->status === 'In Approval'){
                return True;
            }else{
                return False;
            }
        }

        return False;
    }

    //
    public function approveNotReview(Request $request)
    {
        $form_no = $request->query('form_no');
        $approver_nik = $request->query('approver_nik');
        $status = $request->query('status');
        $token = $request->query('token');
        $approver = User::where('nik', $approver_nik)->first();
        $result = '';
        DB::beginTransaction();
        try {
            $form = MpdrForm::where('no', $form_no)->first();

            // Cek apakah status initiator pending dan token tidak null
            if($form->initiatorDetail->status == 'pending' && $form->initiatorDetail->token !== null){
                // Cek apakah yang login initiator
                if($form->initiatorDetail->initiator_nik == $approver_nik){
                    $form->initiatorDetail->status = $status;
                    $form->initiatorDetail->approved_date = now();
                    $form->initiatorDetail->token = null;
                    $form->initiatorDetail->save();
                    if($status !== 'approve' && $status !== 'approve with review'){
                        $form->status = 'Rejected';
                        $form->prempdr_no = null;
                        $form->save();

                        // Jika form di reject maka kirim email ke admin saja
                        $this->sendMailToAdmin($form_no, $approver);

                    }else{
                        // kirim update ke admin
                        $this->sendMailToAdmin($form_no, $approver);
                        // Jika form di approve oleh initiator maka kirim email ke semua approver kecuali gm dan ke admin
                        $this->sendMailToApproversExceptGM($form_no);
                    }
                    $result = 'Success';
                }
                else
                {
                    DB::rollback();
                    $result = 'Initiator must approve first.';
                    $status = $status;
                }
            }
            else
            {
                $approverDetail = $form->approvedDetail->where('approver_nik', $approver_nik)->first();
                if($approverDetail->status === 'pending' && $approverDetail->token === $token){
                    $approverDetail->status = $status;
                    $approverDetail->token = null;
                    $approverDetail->approved_date = now();
                    $approverDetail->save();
                    $result = 'Success';


                    if($approver->hasRole('gm'))
                    {
                        foreach($form->approvedDetail as $detail){
                            if($detail->status === 'Vacant' || $detail->status === 'pending'){
                                $detail->approved_date = now();
                                if($status === 'approve' || $status === 'approve with review'){
                                    $detail->status = 'approve with review';
                                    $detail->comment = "Approved with GM";
                                }else if($status === 'not approve'){
                                    $detail->status = $status;
                                    $detail->comment = "Not approved by GM";
                                }
                                $detail->token = null;
                                $detail->save();
                            }
                        }
                    }
                }else{
                    DB::rollback();
                    $result = 'Failed';
                    $status = $approverDetail->status;
                }
            }

            activity()
                ->performedOn($form)
                ->inLog('mpdr')
                ->event('Approve')
                ->causedBy($approver)
                ->withProperties(['no' => $form_no, 'action' => 'approved'])
                ->log('Approve Mpdr Form ' . $form_no . ' by ' . $approver->name . ' at ' . now());

            $this->checkFormInApprovalStatus($form_no); // Cek jika status form
            $this->sendMailToAdmin($form_no, $approver); // Kirim update ke admin
            $this->sendMailToUser($form_no); // Kirim email sesuai status form
            DB::commit();
            return view('emails.mpdr.resultView', compact('form_no', 'result', 'status'));
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            $result = 'Failed';
            return view('emails.mpdr.resultView', compact('form_no', 'result', 'status'));
        }
    }

    public function approveWithReview(Request $request)
    {
        $form_no = $request->query('form_no');
        $approver_nik = $request->query('approver_nik');
        $status = $request->query('status');
        $token = $request->query('token');
        $route_name = 'approveWithReview';
        
        // Load form data dengan semua relasi
        $form = MpdrForm::with([
            'revision', 
            'detail', 
            'category', 
            'channel', 
            'description', 
            'certification', 
            'competitor', 
            'packaging', 
            'market', 
            'approvedDetail',
            'initiatorDetail',
            'user'
        ])->where('no', $form_no)->firstOrFail();
        
        // Get approver data
        $approver = User::where('nik', $approver_nik)->firstOrFail();
        
        return view('emails.mpdr.commentForm', compact('form', 'form_no', 'approver', 'approver_nik', 'status', 'token', 'route_name'));
    }

    public function notApprove(Request $request)
    {
        $form_no = $request->query('form_no');
        $approver_nik = $request->query('approver_nik');
        $status = $request->query('status');
        $token = $request->query('token');
        $route_name = 'notApprove';
        
        // Load form data dengan semua relasi
        $form = MpdrForm::with([
            'revision', 
            'detail', 
            'category', 
            'channel', 
            'description', 
            'certification', 
            'competitor', 
            'packaging', 
            'market', 
            'approvedDetail',
            'initiatorDetail',
            'user'
        ])->where('no', $form_no)->firstOrFail();
        
        // Get approver data
        $approver = User::where('nik', $approver_nik)->firstOrFail();
        
        return view('emails.mpdr.commentForm', compact('form', 'form_no', 'approver', 'approver_nik', 'status', 'token', 'route_name'));
    }

    // fix gm
    public function mailComment(Request $request)
    {
        $form_no = $request->query('form_no');
        $approver_nik = $request->query('approver_nik');
        $status = $request->query('status');
        $token = $request->query('token');
        $comment = $request->input('comment');
        $approver = User::where('nik', $approver_nik)->first();
        $result = '';
        DB::beginTransaction();

        try {
            $form = MpdrForm::where('no', $form_no)->first();

            // Cek apakah status initiator pending dan token tidak null
            if($form->initiatorDetail->status == 'pending' && $form->initiatorDetail->token !== null){
                // Cek apakah yang login initiator

                if($form->initiatorDetail->initiator_nik == $approver_nik && $form->initiatorDetail->token == $token){
                    $form->initiatorDetail->status = $status;
                    $form->initiatorDetail->approved_date = now();
                    if($request->input('action') !== 'approve'){
                        $form->initiatorDetail->comment = $comment;
                    }
                    $form->initiatorDetail->token = null;
                    $form->initiatorDetail->save();
                    $form->save();
                    if($status !== 'approve' && $status !== 'approve with review'){
                        $form->status = 'Rejected';
                        $form->save();

                    }else{
                        // Jika form di approve oleh initiator maka kirim email ke semua approver kecuali gm
                        $this->sendMailToApproversExceptGM($form_no);
                    }

                    $result = 'Success';
                }
                else
                {
                    DB::rollback();
                    $result = 'Initiator must approve first.';
                    $status = $status;
                }
            }
            else
            {
                $approverDetail = $form->approvedDetail->where('approver_nik', $approver_nik)->first();
                if($approverDetail->token === $token){
                    $approverDetail->status = $status;
                    $approverDetail->comment = $comment;
                    $approverDetail->token = null;
                    $approverDetail->approved_date = now();
                    $approverDetail->save();
                    $result = 'Success';

                    if($approver->hasRole('gm'))
                    {
                        foreach($form->approvedDetail as $detail){
                            if($detail->status === 'Vacant' || $detail->status === 'pending'){
                                $detail->approved_date = now();
                                if($status === 'approve' || $status === 'approve with review'){
                                    $detail->status = 'approve with review';
                                    $detail->comment = "Approved with GM";
                                }else if($status === 'not approve'){
                                    $detail->status = $status;
                                    $detail->comment = "Not approved by GM";
                                }
                                $detail->token = null;
                                $detail->save();
                            }
                        }
                    }
                }else{

                    DB::rollback();
                    $result = 'You have already approved this form previously.';
                    $status = $approverDetail->status;
                }
            }

            activity()
                ->performedOn($form)
                ->inLog('mpdr')
                ->event(ucfirst($status))
                ->causedBy($approver)
                ->withProperties(['no' => $form_no, 'action' => $status === 'not approve' ? 'not approved' : ($status === 'approve with review' ? 'approved with review' : 'approved')])
                ->log(ucfirst($status) . ' Mpdr Form ' . $form_no . ' by ' . $approver->name . ' at ' . now());


            $this->checkFormInApprovalStatus($form_no); // Cek jika status form
            $this->sendMailToAdmin($form_no, $approver); // Kirim update ke admin
            $this->sendMailToUser($form_no); // Kirim email sesuai status form
            DB::commit();
            return view('emails.mpdr.resultView', compact('form_no', 'result', 'status'));
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            $result = 'Failed';
            return view('emails.mpdr.resultView', compact('form_no', 'result', 'status'));
        }
    }


}
