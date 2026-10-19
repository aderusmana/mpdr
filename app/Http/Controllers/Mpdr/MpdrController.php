<?php

namespace App\Http\Controllers\Mpdr;

use App\Http\Controllers\Controller;
use App\Jobs\Mpdr\ProcessApproval;
use App\Models\MPDR\MpdrApprovedDetail;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\MPDR\MpdrForm;
use App\Models\MPDR\MpdrApprover;
use App\Models\MPDR\MpdrRevision;
use App\Models\PREMPDR\PreMpdrForm;
use App\Models\PREMPDR\PreMpdrFrom;
use App\Notifications\MpdrNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\ConditionQueryTrait;
use Spatie\Activitylog\Models\Activity as SpatieActivity;

class MpdrController extends Controller
{
    use ConditionQueryTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        confirmDelete();
        return \view('page.mpdr.list-mpdr');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return \view('page.mpdr.create-mpdr');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'form_status' => 'required|string|in:Draft,Submit',
            'no_reg' => 'required|string',
            'no_pre' => 'required|string',
            'typeOfRequest' => 'required|string|in:New Product,Existing Product',
            'requester_id' => 'nullable|exists:users,id',
            'productName' => 'required|string|max:255',
            'levelPriority' => 'required',
            'initiator_name_display' => '',
            'initiator' => 'nullable|exists:users,nik',
            'rationalForDevelopment' => 'required',
            'productCategory' => 'required',
            'productCategoryText' => '',
            'channel' => 'required',
            'country' => '',
            'productDescription' => 'required',
            'usageDescription' => 'required|string',
            'storageTemperature' => 'required|string',
            'deliveryTemperature' => 'required|string',
            'certification' => 'required',
            'certificationText' => '',
            'competitor_names.*' => 'nullable|string|max:255',
            'competitor_sizes.*' => 'nullable|string|max:255',
            'competitor_packagings.*' => 'nullable|string|max:255',
            'competitor_prices.*' => 'nullable|string|max:255',
            'weightProduct' => 'required',
            'packaging' => 'required',
            'ExistingPackagingText' => '',
            'NewPackagingText' => '',
            'productVariation' => 'required',
            'potentialVolume' => 'required',
            'expectedMargin' => 'required',
            'priceEstimate' => 'required',
            'targetLaunchText' => 'required'
        ]);

        // Mulai transaction untuk memastikan integritas data
        DB::beginTransaction();

        try {
            $user = Auth::user();
            $revision = MpdrRevision::latest()->first();
            // dd($validated['no_pre']);

            // Determine requester user (prefer requester_id if provided)
            $requesterUser = null;
            if(!empty($validated['requester_id'])){
                $requesterUser = User::find($validated['requester_id']);
            }
            if(!$requesterUser){
                $requesterUser = User::where('nik', $validated['initiator'])->first();
            }

            $form = MpdrForm::create([
                'no' => $validated['no_reg'],
                'user_id' => Auth::user()->id,
                'revision_id' => $revision->id,
                'product_name' => $validated['productName'],
                'type_of_request' => $validated['typeOfRequest'] ?? null,
                'requester_id' => $validated['requester_id'] ?? null,
                'initiator' => $requesterUser ? $requesterUser->name : (User::where('nik', $validated['initiator'])->first()->name ?? null),
                'level_priority' => $validated['levelPriority'],
                'status' => $validated['form_status'] == 'Submit' ? 'In Approval' : $validated['form_status'],
                'prempdr_no' => $validated['no_pre'],
            ]);

            // Simpan FormDetail terkait dengan Form
            $form->detail()->create([
                'form_id' => $form->id,
                'rational_for_development' => $validated['rationalForDevelopment'],
                'target_launch' => $validated['targetLaunchText']
            ]);
            $form->category()->create([
                'form_id' => $form->id,
                'category' => $validated['productCategory'],
                'other' => $validated['productCategoryText']
            ]);
            $form->channel()->create([
                'form_id' => $form->id,
                'category' => $validated['channel'],
                'country' => $validated['country']
            ]);
            $form->description()->create([
                'form_id' => $form->id,
                'product_description' => $validated['productDescription'],
                'usage_description' => $validated['usageDescription'],
                'storage_temperature' => $validated['storageTemperature'],
                'delivery_temperature' => $validated['deliveryTemperature']
            ]);
            $form->certification()->create([
                'form_id' => $form->id,
                'category' => $validated['certification'],
                'other' => $validated['certificationText']
            ]);
            $form->competitor()->delete();

            if ($request->has('competitor_names')) {
                foreach ($validated['competitor_names'] as $index => $name) {
                    if (!empty($name)) {
                        $form->competitor()->create([
                            'name'      => $name,
                            'size'      => $validated['competitor_sizes'][$index] ?? null,
                            'packaging' => $validated['competitor_packagings'][$index] ?? null,
                            'price'     => $validated['competitor_prices'][$index] ?? null,
                        ]);
                    }
                }
            }
            $form->packaging()->create([
                'form_id' => $form->id,
                'weight' => $validated['weightProduct'],
                'category' => $validated['packaging'],
                'detail' => $validated[$validated['packaging'].'PackagingText'],
                'product_variation' => $validated['productVariation']
            ]);
            $form->market()->create([
                'form_id' => $form->id,
                'potential_volume' => $validated['potentialVolume'],
                'expected_margin' => $validated['expectedMargin'],
                'price_estimate' => $validated['priceEstimate']
            ]);

            $form->initiatorDetail()->create([
                'form_id' => $form->id,
                'initiator_nik' => $requesterUser ? $requesterUser->nik : $validated['initiator'],
                'initiator_name' => $requesterUser ? $requesterUser->name : (User::where('nik', $validated['initiator'])->first()->name ?? null),
                'status' => 'pending',
                'token' => Str::uuid()
            ]);

            if($validated['form_status'] == 'Submit'){
                $approvers = MpdrApprover::where('user_nik', $user->nik)->get();
                if ($approvers->isEmpty()) {
                    $firstUserNik = MpdrApprover::value('user_nik');
                    if ($firstUserNik) {
                        $approvers = MpdrApprover::where('user_nik', $firstUserNik)->get();
                    }
                }
                foreach($approvers as $index => $approver){
                    $form->approvedDetail()->create([
                        'form_id' => $form->id,
                        'approver_nik' => $approver->approver_nik,
                        'approver_name' => $approver->approver_name,
                        'status' => $approver->approver_status == 'Active' ? 'pending' : 'Vacant',
                        'token' => $approver->approver_status == 'Active' ? Str::uuid() : null
                    ]);
                }

                $gm = User::role('gm')->first();
                if ($gm && !$form->approvedDetail()->where('approver_nik', $gm->nik)->exists()) {
                    $form->approvedDetail()->create([
                        'form_id' => $form->id,
                        'approver_nik' => $gm->nik,
                        'approver_name' => $gm->name,
                        'status' => 'pending',
                        'token' => Str::uuid()
                    ]);
                }

                // Since we route directly to approvers, mark initiator detail as Vacant so it won't block approvals
                $form->initiatorDetail()->update([
                    'status' => 'Vacant',
                    'token' => null
                ]);

                // collect flag to send notifications after commit
                $sendApproverNotifications = true;
            }

            activity()
                ->performedOn($form)
                ->inLog('mpdr')
                ->event('Create')
                ->causedBy($user)
                ->withProperties(['no' => $validated['no_reg'], 'action' => 'created'])
                ->log('Create Mpdr Form ' . $validated['no_reg'] . ' by ' . $user->name . ' at ' . now());

            if($form->status == 'In Approval')
            {
                activity()
                    ->performedOn($form)
                    ->inLog('mpdr')
                    ->event('Submit')
                    ->causedBy($user)
                    ->withProperties(['no' => $validated['no_reg'], 'action' => 'submitted'])
                    ->log('Submit Mpdr Form ' . $validated['no_reg'] . ' by ' . $user->name . ' at ' . now());
            }

            // Commit transaksi
            DB::commit();

            // After commit, dispatch approval emails/notifications for pending approvers (except GM)
            if(!empty($sendApproverNotifications) && isset($form->id)){
                $formWithDetails = MpdrForm::with('approvedDetail')->where('id', $form->id)->first();
                foreach($formWithDetails->approvedDetail as $detail){
                    $approverUser = User::where('nik', $detail->approver_nik)->first();
                    if($approverUser && !$approverUser->hasRole('gm') && $detail->status == 'pending'){
                        ProcessApproval::dispatch($approverUser, $formWithDetails, $detail)->delay(10);

                        $data = [
                            'title' => 'New MPDR for Approval',
                            'message' => $form->no . ' needs your approval.',
                            'user_id' => $form->user_id,
                        ];
                        $notificationType = 'approval_request';
                        $approverUser->notify(new MpdrNotification($data, $notificationType));
                    }
                }
            }

            return response()->json(['success' => true, 'message' => 'Form and details successfully saved!']);

        } catch (\Exception $e) {
            dd($e);
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            Alert::toast('There was an error saving the form.'.$e->getMessage(), 'error');
            return back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($no_reg)
    {
        return view('page.mpdr.form-mpdr')->with('no_reg', $no_reg);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $no_reg)
    {
        return \view('page.mpdr.edit-mpdr')->with('no_reg', $no_reg);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $no_reg)
    {
        $validated = $request->validate([
            'form_status' => 'required|string|in:Draft,Submit',
            'no_reg' => 'required|string',
            'productName' => 'required|string|max:255',
            'levelPriority' => 'required',
            'initiator_name_display' => '',
            'initiator' => 'nullable|exists:users,nik',
            'typeOfRequest' => 'nullable|string|in:New Product,Existing Product',
            'requester_id' => 'nullable|exists:users,id',
            'rationalForDevelopment' => 'required',
            'productCategory' => 'required',
            'productCategoryText' => '',
            'channel' => 'required',
            'country' => '',
            'productDescription' => 'required',
            'usageDescription' => 'required|string',
            'storageTemperature' => 'required|string',
            'deliveryTemperature' => 'required|string',
            'certification' => 'required',
            'certificationText' => '',
            'competitor_names.*' => 'nullable|string|max:255',
            'competitor_sizes.*' => 'nullable|string|max:255',
            'competitor_packagings.*' => 'nullable|string|max:255',
            'competitor_prices.*' => 'nullable|string|max:255',
            'weightProduct' => 'required',
            'packaging' => 'required',
            'ExistingPackagingText' => '',
            'NewPackagingText' => '',
            'productVariation' => 'required',
            'potentialVolume' => 'required',
            'expectedMargin' => 'required',
            'priceEstimate' => 'required',
            'targetLaunchText' => 'required'
        ]);

        // Mulai transaction untuk memastikan integritas data
        DB::beginTransaction();

        try {
            $user = Auth::user();
            $form = MpdrForm::where('no', $validated['no_reg'])
            ->where('user_id', $user->id)
            ->first();

            // Determine requester user (prefer requester_id if provided)
            $requesterUser = null;
            if(!empty($validated['requester_id'])){
                $requesterUser = User::find($validated['requester_id']);
            }
            if(!$requesterUser && !empty($validated['initiator'])){
                $requesterUser = User::where('nik', $validated['initiator'])->first();
            }

            $initiatorName = $requesterUser ? $requesterUser->name : ($form->initiator ?? $user->name);

            $form->update([
                'initiator' => $initiatorName,
                'status' => $validated['form_status'] == 'Submit' ? 'In Approval' : $validated['form_status'],
                'type_of_request' => $validated['typeOfRequest'] ?? $form->type_of_request,
                'requester_id' => $validated['requester_id'] ?? $form->requester_id,
                'gm_direct_approval' => false,
            ]);

            // Simpan FormDetail terkait dengan Form
            $form->detail()->update([
                'rational_for_development' => $validated['rationalForDevelopment'],
                'target_launch' => $validated['targetLaunchText']
            ]);
            $form->category()->update([
                'category' => $validated['productCategory'],
                'other' => $validated['productCategoryText']
            ]);
            $form->channel()->update([
                'category' => $validated['channel'],
                'country' => $validated['country']
            ]);
            $form->description()->update([
                'product_description' => $validated['productDescription'],
                'usage_description' => $validated['usageDescription'],
                'storage_temperature' => $validated['storageTemperature'],
                'delivery_temperature' => $validated['deliveryTemperature']
            ]);
            $form->certification()->update([
                'category' => $validated['certification'],
                'other' => $validated['certificationText']
            ]);

            $form->competitor()->delete();

            if ($request->has('competitor_names')) {
                foreach ($validated['competitor_names'] as $index => $name) {
                    if (!empty($name)) {
                        $form->competitor()->create([
                            'name'      => $name,
                            'size'      => $validated['competitor_sizes'][$index] ?? null,
                            'packaging' => $validated['competitor_packagings'][$index] ?? null,
                            'price'     => $validated['competitor_prices'][$index] ?? null,
                        ]);
                    }
                }
            }
            $form->packaging()->update([
                'weight' => $validated['weightProduct'],
                'category' => $validated['packaging'],
                'detail' => $validated[$validated['packaging'].'PackagingText'],
                'product_variation' => $validated['productVariation']
            ]);
            $form->market()->update([
                'potential_volume' => $validated['potentialVolume'],
                'expected_margin' => $validated['expectedMargin'],
                'price_estimate' => $validated['priceEstimate']
            ]);

            $form->initiatorDetail()->update([
                'initiator_nik' => $requesterUser ? $requesterUser->nik : $validated['initiator'],
                'initiator_name' => $requesterUser ? $requesterUser->name : (User::where('nik', $validated['initiator'])->first()->name ?? null),
                'status' => 'pending',
                'token' => Str::uuid()
            ]);

            if($validated['form_status'] == 'Submit'){
                $approvers = MpdrApprover::where('user_nik', $user->nik)->get();
                if ($approvers->isEmpty()) {
                    $firstUserNik = MpdrApprover::value('user_nik');
                    if ($firstUserNik) {
                        $approvers = MpdrApprover::where('user_nik', $firstUserNik)->get();
                    }
                }
                foreach($approvers as $index => $approver){
                    $form->approvedDetail()->create([
                        'form_id' => $form->id,
                        'approver_nik' => $approver->approver_nik,
                        'approver_name' => $approver->approver_name,
                        'status' => $approver->approver_status == 'Active' ? 'pending' : 'Vacant',
                        'token' => $approver->approver_status == 'Active' ? Str::uuid() : null
                    ]);
                }

                $gm = User::role('gm')->first();
                if ($gm && !$form->approvedDetail()->where('approver_nik', $gm->nik)->exists()) {
                    $form->approvedDetail()->create([
                        'form_id' => $form->id,
                        'approver_nik' => $gm->nik,
                        'approver_name' => $gm->name,
                        'status' => 'pending',
                        'token' => Str::uuid()
                    ]);
                }

                // Mark initiator as Vacant so approvers can act directly
                $form->initiatorDetail()->update([
                    'status' => 'Vacant',
                    'token' => null
                ]);

                $sendApproverNotifications = true;
            }

            activity()
                ->performedOn($form)
                ->inLog('mpdr')
                ->event('Update')
                ->causedBy($user)
                ->withProperties(['no' => $validated['no_reg'], 'action' => 'updated'])
                ->log('Update Mpdr Form ' . $validated['no_reg'] . ' by ' . $user->name . ' at ' . now());

            if($form->status == 'In Approval')
            {
                activity()
                    ->performedOn($form)
                    ->inLog('mpdr')
                    ->event('Submit')
                    ->causedBy($user)
                    ->withProperties(['no' => $validated['no_reg'], 'action' => 'submitted'])
                    ->log('Submit Mpdr Form ' . $validated['no_reg'] . ' by ' . $user->name . ' at ' . now());
            }

            // Commit transaksi
            DB::commit();

            // After commit, dispatch approver notifications (except GM) if needed
            if(!empty($sendApproverNotifications) && isset($form->id)){
                $formWithDetails = MpdrForm::with('approvedDetail')->where('id', $form->id)->first();
                foreach($formWithDetails->approvedDetail as $detail){
                    $approverUser = User::where('nik', $detail->approver_nik)->first();
                    if($approverUser && !$approverUser->hasRole('gm') && $detail->status == 'pending'){
                        ProcessApproval::dispatch($approverUser, $formWithDetails, $detail)->delay(10);

                        $data = [
                            'title' => 'New MPDR for Approval',
                            'message' => $form->no . ' needs your approval.',
                            'user_id' => $form->user_id,
                        ];
                        $notificationType = 'approval_request';
                        $approverUser->notify(new MpdrNotification($data, $notificationType));
                    }
                }
            }

            return response()->json(['success' => true, 'message' => 'Form and details successfully updated!']);

        } catch (\Exception $e) {
            // dd($e);
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            Alert::toast('There was an error saving the form.'.$e->getMessage(), 'error');
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $no)
    {
        $user = Auth::user();
        DB::beginTransaction();

        try {
            $form = MpdrForm::where('no', $no)
                    ->where('status', 'Draft')
                    ->first();
            $form->delete();
            DB::commit();

            activity()
            ->performedOn($form)
            ->inLog('mpdr')
            ->event('Delete')
            ->causedBy($user)
            ->withProperties(['no' => $no, 'action' => 'deleted'])
            ->log('Delete Mpdr Form ' . $no . ' by ' . $user->name . ' at ' . now());

            Alert::toast('Form successfully deleted!.', 'success');
            return redirect()->route('mpdr.index');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            Alert::toast('There was an error updating the form.'.$e->getMessage(), 'error');
            return back();
        }
    }

    public function report()
    {
        return view('page.mpdr.report-mpdr');
    }

    public function log()
    {
        return view('page.mpdr.log-mpdr');
    }

    public function viewForm()
    {
        return view('page.mpdr.form-mpdr');
    }

    // public function print($no_reg)
    // {
    //     return view('page.mpdr.print-mpdr')->with('no_reg', $no_reg);
    // }

    public function template()
    {
        return view('page.mpdr.template-form-mpdr');
    }

    public function noReg()
    {
        $year = date('y');
        $prefix = "{$year}MPDR";
        $lastPcr = MpdrForm::where('no', 'like', "{$prefix}%")->orderBy('no', 'desc')->first();

        if ($lastPcr) {
            $lastNo = (int)substr($lastPcr->no_reg, -4);
            $newNo = $lastNo + 1;
        } else {
            $newNo = 1;
        }

        do {
            $newNoReg = $prefix . str_pad($newNo, 4, '0', STR_PAD_LEFT);
            $existingPcr = MpdrForm::where('no', $newNoReg)->first();
            if ($existingPcr) {
                $newNo++;
            }
        } while ($existingPcr);

        return response()->json(['no_reg' => $newNoReg]);
    }

    public function getFormList(Request $request)
    {
        $query = MpdrForm::with(['approvedDetail', 'initiatorDetail']);

        if ($request->has('searchBuilder')) {
            $this->applySearchBuilder($query, $request->input('searchBuilder'));
        }

        $forms = $query->latest()->get();

            // Proses setiap form untuk menambahkan data yang diperlukan frontend
            $forms->map(function ($form) {
                // Set nilai default
                $form->route_to = null;
                $form->approved_count = 0;
                $form->total_approvers = 0;
                $form->is_route_to_gm = false; // Tambahkan flag default
                $form->can_direct_to_gm = false;

                if ($form->status == 'In Approval') {
                    // Ambil approver yang statusnya 'pending'
                    $pendingApprovers = $form->approvedDetail->where('status', 'pending');

                    // Filter pending approvers: sembunyikan GM jika GM belum eligible
                    $filteredPending = $pendingApprovers->filter(function ($pDetail) use ($form) {
                        $approverUser = User::where('nik', $pDetail->approver_nik)->first();
                        if ($approverUser && $approverUser->hasRole(['gm', 'GM'])) {
                            return $form->isGmEligibleForApproval();
                        }
                        return true;
                    });

                    if ($filteredPending->isNotEmpty()) {
                        // Gabungkan semua nama approver pending yang eligible
                        $form->route_to = $filteredPending->pluck('approver_name')->unique()->implode(', ');

                        // Cek apakah GM ada di antara approver pending yang eligible
                        $hasGm = false;
                        foreach ($filteredPending as $pDetail) {
                            $approverUser = User::where('nik', $pDetail->approver_nik)->first();
                            if ($approverUser && $approverUser->hasRole(['gm', 'GM'])) {
                                $hasGm = true;
                                break;
                            }
                        }
                        $form->is_route_to_gm = $hasGm;
                    } else {
                        $form->route_to = 'Finalizing';
                    }

                    // Vacant positions do not require approval and are excluded from progress.
                    $form->approved_count = $form->approvedDetail->filter(function($detail) {
                        $status = strtolower(trim($detail->status ?? ''));
                        return in_array($status, [
                            'approve',
                            'approved',
                            'approve with review',
                            'approved with review',
                        ], true);
                    })->count();
                    $form->total_approvers = $form->approvedDetail->reject(function($detail) {
                        return strtolower(trim($detail->status ?? '')) === 'vacant';
                    })->count();
                    $hasPendingGm = $pendingApprovers->contains(function ($detail) {
                        $approverUser = User::where('nik', $detail->approver_nik)->first();
                        return $approverUser && $approverUser->hasRole('gm');
                    });
                    $hasRejectedApproval = $form->approvedDetail->contains(function ($detail) {
                        return strtolower(trim($detail->status ?? '')) === 'not approve';
                    });
                    $form->can_direct_to_gm = $hasPendingGm
                        && !$form->gm_direct_approval
                        && !$form->isGmEligibleForApproval()
                        && strtolower(trim($form->initiatorDetail?->status ?? '')) !== 'not approve'
                        && !$hasRejectedApproval;
                }

                return $form;
            });

            return response()->json($forms);
        }


    public function getReportData()
    {
        $form = MpdrForm::with('revision', 'detail', 'category', 'channel', 'description', 'certification', 'competitor', 'packaging', 'market','approvedDetail')->whereIn('status', ['Approved', 'Rejected'])->orderBy('created_at', 'desc')->get();
        if($form){
            $form = $form->map(function($item) {
                /** @var MpdrForm $item */
                $item->new_created_at = $item->created_at->format('j F Y');
                return $item;
            });
            return response()->json($form);
        }

        return response()->json("Tidak ada Form");
    }

    public function printPdf($no_reg)
    {
        // 1. Ambil data form beserta semua relasinya
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
            // UBAH BAGIAN INI
                'approvedDetail' => function ($query) {
                    // SEBELUMNYA: $query->orderBy('level', 'asc');
                    // SESUDAH:
                $query->orderBy('created_at', 'asc'); // Urutkan berdasarkan waktu pembuatan
            }
        ])->where('no', $no_reg)->firstOrFail();

        // 2. Siapkan nama file PDF
        $fileName = 'MPDR-Report-' . $no_reg . '.pdf';

        // 3. Muat view dan passing data ke dalamnya
        $pdf = Pdf::loadView('page.mpdr.print-mpdr', ['form' => $form]);

        // 4. Atur orientasi kertas menjadi landscape jika perlu
        $pdf->setPaper('a4', 'portrait');

        // 5. Kirim PDF ke browser untuk ditampilkan atau di-download
        return $pdf->stream($fileName);
    }

    // public function getPrintData(Request $request)
    // {
    //     $form = MpdrForm::with('revision', 'detail', 'category', 'channel', 'description', 'certification', 'competitor', 'packaging', 'market', 'approvedDetail')->where('no', $request->input('id'))->first();
    //     if($form){
    //         return response()->json($form);
    //     }

    //     return response()->json("Tidak ada Form");
    // }

    public function getPrempdrList()
    {
        $forms = PreMpdrForm::whereDoesntHave('Mpdr')->where('status', 'Approved')->pluck('no');
        if($forms){
            return response()->json($forms);
        }

        return response()->json("Tidak ada Form");
    }

    public function getpremprdtompdr(Request $request)
    {
        $form = PreMpdrForm::with('user','detail', 'category', 'channel', 'description', 'certification', 'competitor', 'packaging', 'market')->where('no', $request->input('no_reg'))->first();
        if($form){
            return response()->json($form);
        }

        return response()->json("Tidak ada Form");
    }

    public function showDetail($formNo)
    {
        $formData = MpdrForm::with('revision', 'detail', 'category', 'channel', 'description', 'certification', 'competitor', 'packaging', 'market', 'approvedDetail', 'initiatorDetail', 'preMpdr')
            ->where('no', $formNo)->firstOrFail();
        // eager load requester relation if present
        $formData->load('requester');
        if ($formData->preMpdr) {
            $formData->brand_name = $formData->preMpdr->brand_name;
        }

        // Prepare ordered approval details for table rendering
        $ordered = $formData->approvedDetail()->orderBy('created_at', 'asc')->get();
        $formData->approved_detail_ordered = $ordered->filter(function($d) use ($formData) {
            $approverUser = User::where('nik', $d->approver_nik)->first();
            if ($approverUser && $approverUser->hasRole(['gm', 'GM'])) {
                if (strtolower($d->status ?? '') === 'pending' && !$formData->isGmEligibleForApproval()) {
                    return false;
                }
            }
            return true;
        })->map(function($d){
            // resolve approver position via User relation
            $approverUser = User::where('nik', $d->approver_nik)->with('position')->first();
            $positionName = null;
            if ($approverUser && $approverUser->position) {
                $positionName = $approverUser->position->name;
            }

            return [
                'approver_name' => $d->approver_name,
                'approver_nik' => $d->approver_nik,
                'approver_position' => $positionName,
                'status' => $d->status,
                'approved_date' => $d->approved_date ? (is_string($d->approved_date) ? $d->approved_date : $d->approved_date->toDateTimeString()) : null,
                'comment' => $d->comment,
            ];
        })->values()->toArray();

        // Attach activity history (Spatie)
        try {
            $history = SpatieActivity::where('log_name', 'mpdr')
                        ->where('subject_type', MpdrForm::class)
                        ->where('subject_id', $formData->id)
                        ->orderBy('created_at', 'desc')
                        ->get()
                        ->map(function($h){
                            return [
                                'id' => $h->id,
                                'description' => $h->description,
                                'properties' => $h->properties ?? null,
                                'causer' => $h->causer ? ($h->causer->name ?? $h->causer->id) : null,
                                'created_at' => $h->created_at->toDateTimeString(),
                            ];
                        })->toArray();
        } catch (\Throwable $e) {
            $history = [];
        }

        $formData->history = $history;

        return response()->json($formData);
    }
}
