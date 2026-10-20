<?php

namespace App\Http\Controllers\PreMpdr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\PREMPDR\PreMpdrForm;
use App\Models\PREMPDR\PreMpdrRevision;
use App\Jobs\PreMpdr\ProcessApproval;
use App\Jobs\PreMpdr\sendResultToUser;
use App\Models\PREMPDR\PreMpdrApprover;
use App\Notifications\MpdrNotification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PreMpdrApprovalController extends Controller
{


    public function viewApprovalForm($no_reg)
    {
        confirmDelete();
        return view('page.pre-mpdr.form-approval-prempdr')->with('no_reg', $no_reg);
    }

    public function getApproverAvailableLevels(){
        $all_levels = range(2, 10);

        $used_levels = PreMpdrApprover::pluck('level')->toArray();

        $available_levels = array_diff($all_levels, $used_levels);

        return response()->json(array_values($available_levels));
    }
    
    public function approveForm(Request $request, $no_reg)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|string|in:approve,approve with review,not approve',
            'comment' => 'required_if:action,approve with review,not approve|nullable|string',
            'target_approver_nik' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        DB::beginTransaction();
        try {
            /** @var User $user */
            $user = Auth::user();
            $nik = $user->nik;
            $form = PreMpdrForm::where('no', $no_reg)->lockForUpdate()->first();

            if (!$form) {
                DB::rollBack();
                return response()->json(['message' => 'Form not found.'], 404);
            }

            $detailToUpdate = $this->nextPendingApproval($form, true);
            $isAdmin = $user->hasRole(['super-admin', 'Super-Admin']) || $user->can('super-admin');

            if (!$detailToUpdate || (!$isAdmin && $detailToUpdate->approver !== $nik)) {
                DB::rollBack();
                return response()->json(['message' => 'You have already processed this form or it is not your turn.'], 403);
            }

            if ($isAdmin && $request->filled('target_approver_nik') && $detailToUpdate->approver !== $request->input('target_approver_nik')) {
                DB::rollBack();
                return response()->json(['message' => 'Only the current approver can be taken over.'], 403);
            }

            $detailToUpdate->status = $request->input('action');
            $detailToUpdate->approved_date = now();
            if ($request->input('action') !== 'approve') {
                $detailToUpdate->comment = $request->input('comment');
            }
            $detailToUpdate->token = null;
            $detailToUpdate->save();

            $logNote = ucfirst($request->input('action')) . ' PreMpdr Form ' . $no_reg . ' by ' . $user->name;
            if ($user->nik !== $detailToUpdate->approver) {
                $logNote .= ' (Takeover on behalf of ' . ($detailToUpdate->name ?? $detailToUpdate->approver) . ')';
            }

            activity()
                ->performedOn($form)
                ->inLog('prempdr')
                ->event(ucfirst($request->input('action')))
                ->causedBy($user)
                ->withProperties([
                    'no' => $no_reg,
                    'action' => $request->input('action'),
                    'takeover' => ($user->nik !== $detailToUpdate->approver),
                    'on_behalf_of' => $detailToUpdate->name ?? $detailToUpdate->approver
                ])
                ->log($logNote);

            if ($this->checkFormInApprovalStatus($no_reg)) {
                $this->sendMailToApprover($no_reg);
                $this->sendMailToUser($no_reg);
            } else {
                $this->sendMailToUser($no_reg);
            }

            DB::commit();
            
            return response()->json([
                'success' => true, 
                'message' => 'Form successfully processed!' . ($user->nik !== $detailToUpdate->approver ? ' (Takeover by Super-Admin)' : '')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false, 
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getApproverListData()
    {
        try {
            $approver = User::with('position')->role(['approver', 'Approver'])->get();
            if ($approver->isEmpty()) {
                $approver = User::with('position')->get();
            }
        } catch (\Exception $e) {
            $approver = User::with('position')->get();
        }
        return response()->json($approver);
    }

    public function getApprovalListData()
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json(['data' => []]);
        }

        $nik = $user->nik;

        // [PERBAIKAN] Tambahkan with('approvedDetail') untuk mengambil data relasi
        $query = PreMpdrForm::with('approvedDetail')
            ->where('status', 'In Approval');

        if ($user->hasRole(['super-admin', 'Super-Admin', 'admin', 'Admin', 'gm', 'GM']) || $user->can('view approval prempdr')) {
            // Super-Admin, Admin, GM, atau pengguna dengan hak akses view approval prempdr dapat melihat seluruh form In Approval
        } else {
            // Approver melihat form di mana dia terdaftar sebagai approver
            $query->whereHas('approvedDetail', function ($q) use ($nik) {
                $q->where('approver', $nik);
            });
        }

        // [PERBAIKAN] Tambahkan orderBy untuk mengurutkan dari yang terbaru
        $forms = $query->orderBy('created_at', 'desc')->get();

        return response()->json(['data' => $forms]);
    }

    public function getApprovalFormData($no_reg)
    {
        return view('page.pre-mpdr.form-approval-prempdr')->with('no_reg', $no_reg);
    }

    // app/Http/Controllers/PreMpdrController.php

    public function destroy($nik)
    {
        try {
            // Cari dan hapus approver berdasarkan NIK approver DAN NIK user yang login
            $deleted = PreMpdrApprover::where('approver_nik', $nik)->delete();

            if ($deleted) {
                return response()->json(['status' => 'success', 'message' => 'Approver has been deleted successfully.']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Approver not found or you do not have permission to delete it.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'A server error occurred.'], 500);
        }
    }

    private function sendMailToApprover($no_reg)
    {
        $form = PreMpdrForm::with('revision', 'detail', 'category', 'channel', 'description', 'certification', 'competitor', 'packaging', 'market', 'approvedDetail', 'user')
        ->where('no', $no_reg)->first();
        if ($form && $form->status === 'In Approval') {
            $approved_detail = $this->nextPendingApproval($form);
            if (!$approved_detail) {
                return;
            }

            if (!$approved_detail->token) {
                $approved_detail->token = Str::uuid();
                $approved_detail->save();
            }

            $form->route_to = $this->approverPositionLabel($approved_detail);
            $form->save();
            $approver = User::where('nik', $approved_detail->approver)->firstOrFail();

            ProcessApproval::dispatch($approver, $form, $approved_detail)->delay(10); // send email

            // kirim notif app
            $data = [
                'title' => 'New Pre-MPDR for Approval',
                'message' => $form->no . ' needs your approval.',
                'user_id' => $form->user_id,
            ];
            $notificationType = 'approval_request'; // Tipe notifikasi yang sesuai
            $approver->notify(new MpdrNotification($data, $notificationType));
        }
    }

    private function sendMailToUser($no_reg)
    {
        $form = PreMpdrForm::with('revision', 'detail', 'category', 'channel', 'description', 'certification', 'competitor', 'packaging', 'market', 'approvedDetail', 'user')
        ->where('no', $no_reg)->first();
        if($form->status == 'Approved'){
            $allApproverNik = $form->approvedDetail()->pluck('approver'); // Semua user yang dituju
            $allApprover = User::whereIn('nik', $allApproverNik)->get(); // Ambil usernya
            $allAdmin = User::whereHas('roles', function($query) {
                $query->where('name', 'admin');
            })->get(); // Semua admin
            $requester = $form->user ? collect([$form->user]) : collect();
            $allUser = $allApprover->merge($allAdmin)->merge($requester)->unique('id');

            foreach($allUser as $user){ // Foreach semua user
                sendResultToUser::dispatch($user,  $form)->delay(10); // send email

                // kirim notif website
                $data = [
                    'title' => 'Pre-MPDR approved',
                    'message' => $form->no . ' has been approved.',
                    'user_id' => $form->user_id,
                ];
                $notificationType = 'approved'; // Tipe notifikasi yang sesuai
                $user->notify(new MpdrNotification($data, $notificationType));
            }

            activity()
                ->performedOn($form)
                ->inLog('prempdr')
                ->event('Final')
                ->withProperties(['no' => $form->no, 'action' => 'approved'])
                ->log($form->no . ' has been Approved at ' . now());

        }else if($form->status == 'Rejected'){
            // Get initiator from level 1 approver
            $initiatorDetail = $form->approvedDetail->where('level', 1)->first();
            $initiator = $initiatorDetail ? User::where('nik', $initiatorDetail->approver)->first() : null;
            $requester = $form->user;
            
            $allAdmin = User::whereHas('roles', function($query) {
                $query->where('name', 'admin');
            })->get(); // Semua admin
            
            // Gabung initiator, admin, dan requester, filter yang null
            $allUser = $allAdmin;
            if ($initiator) {
                $allUser = $allUser->push($initiator);
            }
            if ($requester) {
                $allUser = $allUser->push($requester);
            }
            $allUser = $allUser->unique('id');

            //foreach initiator, admin, dan requester
            foreach($allUser as $user){
                sendResultToUser::dispatch($user, $form)->delay(10); // send email

                // kirim notif website
                $data = [
                    'title' => 'Pre-MPDR rejected',
                    'message' => $form->no . ' has been rejected.',
                    'user_id' => $form->user_id,
                ];
                $notificationType = 'rejected'; // Tipe notifikasi yang sesuai
                $user->notify(new MpdrNotification($data, $notificationType));
            }

            activity()
                ->performedOn($form)
                ->inLog('prempdr')
                ->event('Final')
                ->withProperties(['no' => $form->no, 'action' => 'rejected'])
                ->log($form->no . ' has been Rejected at ' . now());

        }else if($form->status == 'In Approval'){
            $admins = User::whereHas('roles', function($query) {
                $query->where('name', 'admin');
            })->get(); // Semua email admin
            $requester = $form->user ? collect([$form->user]) : collect();
            $allNotify = $admins->merge($requester)->unique('id');

            $lastApproverName = optional($form->approvedDetail->whereIn('status', ['approve', 'approve with review'])->last())->name ?? 'Approver';

            foreach($allNotify as $notifiedUser){ // Foreach semua admin & requester
                sendResultToUser::dispatch($notifiedUser, $form)->delay(10); // send email

                // kirim notif website
                $data = [
                    'title' => 'Pre-MPDR ' . $form->no,
                    'message' => 'No Pre-MPDR ' . $form->no . ' Has been approved by ' . $lastApproverName . '.',
                    'user_id' => $form->user_id,
                ];
                $notificationType = 'updates'; // Tipe notifikasi yang sesuai
                $notifiedUser->notify(new MpdrNotification($data, $notificationType));
            }

        }
    }

    private function checkFormInApprovalStatus($no_reg){
        $form = PreMpdrForm::where('status', 'In Approval')
            ->where('no', $no_reg)->first();
        if($form){
            $details = $form->approvedDetail()
                ->orderBy('level')
                ->orderBy('id')
                ->get();

            if ($details->contains('status', 'not approve')) {
                $form->status = 'Rejected';
                $form->route_to = null;
                $form->save();
                return false;
            }

            $nextApprover = $details->firstWhere('status', null);
            if ($nextApprover) {
                $form->route_to = $this->approverPositionLabel($nextApprover);
                $form->save();
            } else {
                $form->status = 'Approved';
                $form->route_to = null;
                $form->save();
            }

            return $form->status === 'In Approval';
        }

        return false;
    }

    private function nextPendingApproval(PreMpdrForm $form, bool $lock = false)
    {
        $query = $form->approvedDetail()
            ->whereNull('status')
            ->orderBy('level')
            ->orderBy('id');

        if ($lock) {
            $query->lockForUpdate();
        }

        return $query->first();
    }

    private function approverPositionLabel($detail): string
    {
        if ($detail->approver_position) {
            return $detail->approver_position;
        }

        $position = User::with('position')->where('nik', $detail->approver)->first()?->position?->name;

        return $position ?: $detail->name;
    }


    public function approveNotReview(Request $request)
    {
        $form_no = $request->query('form_no');
        $approver_nik = $request->query('approver_nik');
        $status = $request->query('status');
        $token = $request->query('token');
        $result = '';
        $form = null;
        $approver = null;
        $approverDetail = null;
        $comment = null;
        DB::beginTransaction();
        try {
            $form = PreMpdrForm::with(['revision', 'detail', 'category', 'channel', 'description', 'certification', 'competitor', 'packaging', 'market', 'approvedDetail', 'user'])
                ->where('no', $form_no)->lockForUpdate()->first();
            if (!$form) {
                throw new \Exception('Form not found');
            }

            $approverDetail = $this->nextPendingApproval($form, true);
            $approver = User::where('nik', $approver_nik)->first();

            if ($token && $approverDetail && $approverDetail->approver === $approver_nik
                && $approverDetail->token && hash_equals((string) $approverDetail->token, (string) $token)) {
                $approverDetail->status = $status;
                $approverDetail->token = null;
                $approverDetail->approved_date = now();
                $approverDetail->save();
                $result = 'Success';

                activity()
                    ->performedOn($form)
                    ->inLog('prempdr')
                    ->event('Approve')
                    ->causedBy($approver)
                    ->withProperties(['no' => $form_no, 'action' => 'approved'])
                    ->log('Approve PreMpdr Form ' . $form_no . ' by ' . ($approver ? $approver->name : $approver_nik) . ' at ' . now());


                // Cek jika status masuk in approval
                if($this->checkFormInApprovalStatus($form_no)){
                    $this->sendMailToApprover($form_no); // kirim email jika masih ada yang belum approve
                    $this->sendMailToUser($form_no); // kirim email ke admin
                }else{
                    $this->sendMailToUser($form_no);
                }

                DB::commit();
            }else{

                DB::rollback();
                $result = 'You have already approved this form previously.';
                $status = $approverDetail?->status ?? $status;
            }


            return view('emails.pre-mpdr.resultView', compact('form_no', 'result', 'status', 'form', 'approver', 'approverDetail', 'comment'));
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            $result = 'Failed';
            return view('emails.pre-mpdr.resultView', compact('form_no', 'result', 'status', 'form', 'approver', 'approverDetail', 'comment'));
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
        $form = PreMpdrForm::with([
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
            'user'
        ])->where('no', $form_no)->firstOrFail();
        
        // Get approver data
        $approver = User::where('nik', $approver_nik)->firstOrFail();
        
        return view('emails.pre-mpdr.commentForm', compact('form', 'form_no', 'approver', 'approver_nik', 'status', 'token', 'route_name'));
    }

    public function notApprove(Request $request)
    {
        $form_no = $request->query('form_no');
        $approver_nik = $request->query('approver_nik');
        $status = $request->query('status');
        $token = $request->query('token');
        $route_name = 'notApprove';
        
        // Load form data dengan semua relasi
        $form = PreMpdrForm::with([
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
            'user'
        ])->where('no', $form_no)->firstOrFail();
        
        // Get approver data
        $approver = User::where('nik', $approver_nik)->firstOrFail();
        
        return view('emails.pre-mpdr.commentForm', compact('form', 'form_no', 'approver', 'approver_nik', 'status', 'token', 'route_name'));
    }

    public function mailComment(Request $request)
    {
        $form_no = $request->query('form_no');
        $approver_nik = $request->query('approver_nik');
        $status = $request->query('status');
        $token = $request->query('token');
        $comment = $request->input('comment');
        $result = '';
        $form = null;
        $approver = null;
        $approverDetail = null;
        
        // Log untuk debugging
        Log::info('mailComment called', [
            'form_no' => $form_no,
            'approver_nik' => $approver_nik,
            'status' => $status,
            'status_length' => strlen($status),
            'token' => $token
        ]);
        
        DB::beginTransaction();
        try {
            $form = PreMpdrForm::with(['revision', 'detail', 'category', 'channel', 'description', 'certification', 'competitor', 'packaging', 'market', 'approvedDetail', 'user'])
                ->where('no', $form_no)->lockForUpdate()->first();
            if (!$form) {
                throw new \Exception('Form not found');
            }

            $approverDetail = $this->nextPendingApproval($form, true);
            $approver = User::where('nik', $approver_nik)->first();
            
            Log::info('Approver detail found', [
                'approver_nik' => $approver_nik,
                'current_status' => $approverDetail?->status,
            ]);
            if ($token && $approverDetail && $approverDetail->approver === $approver_nik
                && $approverDetail->token && hash_equals((string) $approverDetail->token, (string) $token)) {
                $approverDetail->status = $status;
                $approverDetail->comment = $comment;
                $approverDetail->token = null;
                $approverDetail->approved_date = now();
                $approverDetail->save();
                $result = 'Success';

                activity()
                    ->performedOn($form)
                    ->inLog('prempdr')
                    ->event(ucfirst($status))
                    ->causedBy($approver)
                    ->withProperties(['no' => $form_no, 'action' => $status === 'not approve' ? 'not approved' : ($status === 'approve with review' ? 'approved with review' : 'approved')])
                    ->log(ucfirst($status) .' PreMpdr Form ' . $form_no . ' by ' . ($approver ? $approver->name : $approver_nik) . ' at ' . now());


                // Cek jika status masuk in approval
                if($this->checkFormInApprovalStatus($form_no)){
                    $this->sendMailToApprover($form_no); // kirim email jika masih ada yang belum approve
                    $this->sendMailToUser($form_no); // kirim email ke admin
                }else{
                    $this->sendMailToUser($form_no);
                }

                DB::commit();
            }else{

                DB::rollback();
                $result = 'You have already approved this form previously.';
                $status = $approverDetail?->status ?? $status;
            }


            return view('emails.pre-mpdr.resultView', compact('form_no', 'result', 'status', 'form', 'approver', 'approverDetail', 'comment'));
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            
            // Log error detail
            Log::error('mailComment failed', [
                'form_no' => $form_no ?? 'unknown',
                'approver_nik' => $approver_nik ?? 'unknown',
                'status' => $status ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $result = 'Failed';
            $status = $status ?? 'unknown';
            $form_no = $form_no ?? 'unknown';
            return view('emails.pre-mpdr.resultView', compact('form_no', 'result', 'status', 'form', 'approver', 'approverDetail', 'comment'));
        }
    }

    public function approver()
    {
        $users = User::role('approver')->get();
        confirmDelete();
        return view('page.pre-mpdr.approver-prempdr');
    }

    public function getSelectedApproverList()
    {
        $user_nik = Auth::user()->nik;
        $approverList = PreMpdrApprover::with('approverUser.position')
            ->where('user_nik', $user_nik)
            ->orderBy('level', 'asc')
            ->get();

        if ($approverList->isEmpty()) {
            // Fallback: If logged in user has no custom approver list yet, load system default approvers
            $firstUserNik = PreMpdrApprover::value('user_nik');
            if ($firstUserNik) {
                $approverList = PreMpdrApprover::with('approverUser.position')
                    ->where('user_nik', $firstUserNik)
                    ->orderBy('level', 'asc')
                    ->get();
            }
        }

        $approverList->transform(function ($item) {
            $item->position = $item->approverUser?->position?->name ?? '-';
            return $item;
        });

        return response()->json($approverList);
    }

    public function updateApproverOrder(Request $request)
    {
        // Langkah 1: Validasi semua input yang masuk
        $validator = Validator::make($request->all(), [
            'nik'     => 'present|array', // 'present' berarti field harus ada, tapi boleh array kosong
            'nik.*'   => 'required|string', // Setiap item di dalam array nik harus ada dan berupa string
            'name'    => 'present|array',
            'name.*'  => 'required|string',
            'status'  => 'present|array',
            'status.*' => 'required|string',
            'level'   => 'present|array', // <-- Perbaiki typo dari 'levels' ke 'level'
            'level.*' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid data provided.',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        // Langkah 2: Ambil semua data sekali saja sebelum loop
        $user_nik          = Auth::user()->nik;
        $approver_niks     = $request->input('nik');
        $approver_names    = $request->input('name');
        $approver_statuses = $request->input('status');
        $approver_levels   = $request->input('level'); // <-- Perbaiki typo & simpan di variabel

        // Pastikan semua array punya jumlah elemen yang sama (kecuali jika kosong)
        if (!empty($approver_niks) && (count($approver_niks) !== count($approver_names) || count($approver_niks) !== count($approver_statuses) || count($approver_niks) !== count($approver_levels))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Input arrays have mismatched counts.'
            ], 400); // 400 Bad Request
        }


        // Langkah 3: Jalankan operasi database dalam transaksi
        DB::beginTransaction();
        try {
            // Hapus semua approver lama untuk user ini
            PreMpdrApprover::where('user_nik', $user_nik)->delete();

            // Jika ada data approver baru, buat ulang
            if (!empty($approver_niks)) {
                foreach ($approver_niks as $index => $nik) { // <-- Gunakan variabel $nik
                    PreMpdrApprover::create([
                        'user_nik'        => $user_nik,
                        'approver_nik'    => $nik, // <-- Lebih bersih
                        'approver_name'   => $approver_names[$index],
                        'approver_status' => $approver_statuses[$index],
                        'level'           => $approver_levels[$index]
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'status'  => 'success',
                'message' => 'Approver list has been saved successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'error',
                'message' => 'An error occurred while saving the approver list: ' . $e->getMessage()
            ], 500);
        }
    }

    public function resendEmail($no_reg)
    {
        DB::beginTransaction();
        try {
            $form = PreMpdrForm::where('no', $no_reg)->lockForUpdate()->first();
            if (!$form) {
                DB::rollBack();
                return response()->json(['status' => 'error', 'message' => 'Pre-MPDR form not found.'], 404);
            }

            if ($form->status !== 'In Approval') {
                DB::rollBack();
                return response()->json(['status' => 'error', 'message' => 'Email can only be resent for forms with In Approval status.'], 400);
            }

            $currentApprovedDetail = $form->approvedDetail()
                ->whereNull('status')
                ->orderBy('level', 'asc')
                ->orderBy('id', 'asc')
                ->lockForUpdate()
                ->first();

            if (!$currentApprovedDetail) {
                DB::rollBack();
                return response()->json(['status' => 'error', 'message' => 'There are no active approvers awaiting approval.'], 400);
            }

            // Invalidate token lama: Generasi token UUID baru. Link di email lama otomatis EXPIRED!
            $newToken = (string) Str::uuid();
            $currentApprovedDetail->token = $newToken;
            $currentApprovedDetail->save();

            $approverUser = User::where('nik', $currentApprovedDetail->approver)->first();

            if (!$approverUser) {
                DB::rollBack();
                return response()->json(['status' => 'error', 'message' => 'User data for approver (' . $currentApprovedDetail->name . ') was not found.'], 404);
            }

            $form->route_to = $this->approverPositionLabel($currentApprovedDetail);
            $form->save();

            ProcessApproval::dispatch($approverUser, $form, $currentApprovedDetail)->delay(now()->addSeconds(2));

            $notificationData = [
                'title' => 'Reminder: Pre-MPDR Approval Request',
                'message' => 'Pre-MPDR request ' . $form->no . ' requires your approval (reminder email resent).',
                'form_no' => $form->no,
            ];
            $approverUser->notify(new MpdrNotification($notificationData, 'approval_request'));

            activity()
                ->performedOn($form)
                ->inLog('prempdr')
                ->event('ResendEmail')
                ->causedBy(Auth::user())
                ->withProperties(['no' => $no_reg, 'approver' => $approverUser->name])
                ->log('Resent approval email to ' . $approverUser->name . ' for Pre-MPDR ' . $no_reg);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'The notification email was resent to ' . $approverUser->name . '. The previous email link has expired.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Failed to resend the email: ' . $e->getMessage()], 500);
        }
    }
}
