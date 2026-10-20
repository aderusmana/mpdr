<?php

namespace App\Http\Controllers\PreMpdr;

use App\Http\Controllers\Controller;
use App\Jobs\PreMpdr\ProcessApproval;
use App\Models\PREMPDR\PreMpdrApprovedDetail;
use App\Models\PREMPDR\PreMpdrApprover;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\PREMPDR\PreMpdrForm;
use App\Models\PREMPDR\PreMpdrRevision;
use App\Notifications\MpdrNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\ConditionQueryTrait;
use Spatie\Activitylog\Models\Activity as SpatieActivity;

class PreMpdrController extends Controller
{
    use ConditionQueryTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Delete Form!';
        $text = "Are you sure you want to delete this form?";
        confirmDelete($title, $text);

        return view('page.pre-mpdr.list-prempdr');
    }

    private function approverPositionLabel(PreMpdrApprovedDetail $detail): string
    {
        if ($detail->approver_position) {
            return $detail->approver_position;
        }

        $position = User::with('position')->where('nik', $detail->approver)->first()?->position?->name;

        return $position ?: $detail->name;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // confirmDelete();
        return view('page.pre-mpdr.list -prempdr');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->normalizeMultiSelectInputs($request);

        // 1. Mulai Database Transaction
        // Ini memastikan semua data hanya akan disimpan jika tidak ada error sama sekali.
        DB::beginTransaction();

        try {
            // 2. Validasi semua input dari form
            $validated = $request->validate([
                'form_status' => 'required|string|in:Draft,Submit',
                'no_reg' => 'required|string|unique:pre_mpdr_forms,no',
                'projectName' => 'required|string|max:255',
                'levelPriority' => 'required',
                'brandName' => 'required',
                'rationalForDevelopment' => 'required',
                'productCategory' => 'required',
                'productCategoryText' => 'nullable|string',
                'channel' => 'required|array|min:1',
                'channel.*' => 'required|string|in:International,Regional,Industrial,FSBC-Direct,FSBC-Distributor',
                'country' => 'nullable|string',
                'productDescription' => 'required',
                'usageDescription' => 'required|string',
                'storageTemperature' => 'required|string',
                'deliveryTemperature' => 'required|string',
                'certification' => 'required|array|min:1',
                'certification.*' => 'required|string|in:BPOM,HALAL,Others',
                'certificationText' => 'nullable|string',
                'weightProduct' => 'required|string',
                'packaging' => 'required|string|in:Existing,New',
                'ExistingPackagingText' => ['nullable', 'string', \Illuminate\Validation\Rule::requiredIf($request->input('packaging') === 'Existing')],
                'NewPackagingText' => ['nullable', 'string', \Illuminate\Validation\Rule::requiredIf($request->input('packaging') === 'New')],
                'productVariation' => 'required|string',
                'potentialVolume' => 'required|string',
                'expectedMargin' => 'required',
                'priceEstimate' => 'required',
                'targetLaunchText' => 'required',
                'competitor_names.*' => 'nullable|string|max:255',
                'competitor_sizes.*' => 'nullable|string|max:255',
                'competitor_packagings.*' => 'nullable|string|max:255',
                'competitor_prices.*' => 'nullable|string|max:255',
            ]);
            if (in_array('International', $validated['channel'], true) && blank($validated['country'])) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'country' => 'Country is required when International channel is selected.',
                ]);
            }
            if (in_array('Others', $validated['certification'], true) && blank($validated['certificationText'])) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'certificationText' => 'Please specify the other certification.',
                ]);
            }

            // 3. Persiapan Data Awal
            $user = Auth::user();
            $supervisor = $user->atasan; // Dapatkan atasan user
            $revision = PreMpdrRevision::latest()->firstOrFail(); // Ambil revisi form terakhir

            // Cek apakah atasan ada jika form di-submit
            if ($validated['form_status'] == 'Submit' && !$supervisor?->nik) {
                throw new \Exception('A supervisor could not be found for this user. The form cannot be submitted.');
            }

            // 4. Buat Record Utama (PreMpdrForm)
            $form = PreMpdrForm::create([
                'no' => $validated['no_reg'],
                'user_id' => $user->id,
                'revision_id' => $revision->id,
                'project_name' => $validated['projectName'],
                'brand_name' => $validated['brandName'],
                'level_priority' => $validated['levelPriority'],
                'status' => $validated['form_status'] == 'Submit' ? 'In Approval' : 'Draft',
                'route_to' => $validated['form_status'] == 'Submit' ? ($supervisor->position->name ?? $supervisor->name) : null,
            ]);

            // 5. Simpan Data Detail menggunakan Relasi Eloquent
            // Ini adalah cara yang efisien dan bersih untuk menyimpan data ke tabel terkait.
            $form->detail()->create([
                'rational_for_development' => $validated['rationalForDevelopment'],
                'target_launch' => $validated['targetLaunchText'],
            ]);

            $form->category()->create([
                'category' => $validated['productCategory'],
                'other' => $validated['productCategoryText'],
            ]);

            $form->channel()->create([
                'category' => implode(', ', $validated['channel']),
                'country' => in_array('International', $validated['channel'], true) ? $validated['country'] : null,
            ]);

            $form->description()->create([
                'product_description' => $validated['productDescription'],
                'usage_description' => $validated['usageDescription'],
                'storage_temperature' => $validated['storageTemperature'],
                'delivery_temperature' => $validated['deliveryTemperature'],
            ]);

            $form->certification()->create([
                'category' => implode(', ', $validated['certification']),
                'other' => in_array('Others', $validated['certification'], true) ? $validated['certificationText'] : null,
            ]);

            // Simpan data kompetitor (jika ada)
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
                'weight' => $validated['weightProduct'],
                'category' => $validated['packaging'],
                'detail' => $validated[$validated['packaging'].'PackagingText'] ?? null,
                'product_variation' => $validated['productVariation'],
            ]);

            $form->market()->create([
                'potential_volume' => $validated['potentialVolume'],
                'expected_margin' => $validated['expectedMargin'],
                'price_estimate' => $validated['priceEstimate'],
            ]);

            // Jika form di-submit, tambahkan approver selanjutnya dari daftar master
            if ($validated['form_status'] == 'Submit') {
                // 6. Atur Alur Persetujuan (Approval Workflow)
                // Tambahkan atasan sebagai approver pertama (level 1)
                $firstApproverDetail = $form->approvedDetail()->create([
                    'approver' => $supervisor->nik,
                    'name' => $supervisor->name,
                    'approver_position' => $supervisor->position->name ?? null,
                    'level' => 1,
                    'token' => Str::uuid(),
                ]);

                $masterApprovers = PreMpdrApprover::where('user_nik', $user->nik)->orderBy('level', 'asc')->get();
                if ($masterApprovers->isEmpty()) {
                    $firstUserNik = PreMpdrApprover::value('user_nik');
                    if ($firstUserNik) {
                        $masterApprovers = PreMpdrApprover::where('user_nik', $firstUserNik)->orderBy('level', 'asc')->get();
                    }
                }

                foreach ($masterApprovers as $approver) {
                    $form->approvedDetail()->create([
                        'approver' => $approver->approver_nik,
                        'name' => $approver->approver_name,
                        'approver_position' => User::with('position')->where('nik', $approver->approver_nik)->first()?->position?->name,
                        'level' => $approver->level,
                        'token' => Str::uuid(),
                    ]);
                }

                // 7. Kirim Notifikasi (Sistem & Email) HANYA ke Approver Pertama
                $firstApproverUser = User::where('nik', $supervisor->nik)->first();
                if ($firstApproverUser) {
                    // a. Kirim notifikasi sistem (in-app notification)
                    $notificationData = [
                        'title' => 'Pre-MPDR Approval Request',
                        'message' => 'Pre-MPDR request ' . $form->no . ' requires your approval.',
                        'form_no' => $form->no,
                    ];
                    $firstApproverUser->notify(new MpdrNotification($notificationData, 'approval_request'));

                    // b. Kirim email (menggunakan Job agar tidak memperlambat response)
                    ProcessApproval::dispatch($firstApproverUser, $form, $firstApproverDetail)->delay(now()->addSeconds(10));
                }
            }

            // 8. Catat Aktivitas (Activity Log)
            activity()
                ->performedOn($form)
                ->inLog('prempdr')
                ->event('Create')
                ->causedBy($user)
                ->withProperties(['no' => $form->no, 'action' => 'created'])
                ->log('Created Pre-MPDR form ' . $form->no);

            // 9. Commit Transaksi
            // Jika semua langkah di atas berhasil, simpan semua perubahan ke database.
            DB::commit();

            // 10. Kirim Response Sukses
            return response()->json(['success' => true, 'message' => 'The form was created successfully.']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Jika validasi gagal, batalkan semua operasi database
            DB::rollback();
            // Kembalikan pesan error validasi dalam format JSON
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);

        } catch (\Exception $e) {
            // Jika terjadi error lain, batalkan semua operasi database
            DB::rollback();
            // Kembalikan pesan error umum dalam format JSON
            return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($no_reg)
    {
        return view('page.pre-mpdr.form-prempdr')->with('no_reg', $no_reg);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $no_reg)
    {
        confirmDelete();
        return view('page.pre-mpdr.edit-prempdr')->with('no_reg', $no_reg);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $no_reg)
    {
        $this->normalizeMultiSelectInputs($request);

        $validator = Validator::make($request->all(), [
            'form_status' => 'required|string|in:Draft,Submit',
                'no_reg' => 'required|string',
                'projectName' => 'required|string|max:255',
                'levelPriority' => 'required',
                'brandName' => 'required',
                'rationalForDevelopment' => 'required',
                'productCategory' => 'required',
                'productCategoryText' => 'nullable|string',
                'channel' => 'required|array|min:1',
                'channel.*' => 'required|string|in:International,Regional,Industrial,FSBC-Direct,FSBC-Distributor',
                'country' => 'nullable|string',
                'productDescription' => 'required',
                'usageDescription' => 'required|string',
                'storageTemperature' => 'required|string',
                'deliveryTemperature' => 'required|string',
                'certification' => 'required|array|min:1',
                'certification.*' => 'required|string|in:BPOM,HALAL,Others',
                'certificationText' => 'nullable|string',
                'weightProduct' => 'required|string',
                'packaging' => 'required|string|in:Existing,New',
                'ExistingPackagingText' => ['nullable', 'string', \Illuminate\Validation\Rule::requiredIf($request->input('packaging') === 'Existing')],
                'NewPackagingText' => ['nullable', 'string', \Illuminate\Validation\Rule::requiredIf($request->input('packaging') === 'New')],
                'productVariation' => 'required|string',
                'potentialVolume' => 'required|string',
                'expectedMargin' => 'required',
                'priceEstimate' => 'required',
                'targetLaunchText' => 'required',
                'competitor_names.*' => 'nullable|string|max:255',
                'competitor_sizes.*' => 'nullable|string|max:255',
                'competitor_packagings.*' => 'nullable|string|max:255',
                'competitor_prices.*' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }
        $validated = $validator->validated();
        if (in_array('International', $validated['channel'], true) && blank($validated['country'])) {
            return response()->json(['success' => false, 'errors' => ['country' => ['Country is required when International channel is selected.']]], 422);
        }
        if (in_array('Others', $validated['certification'], true) && blank($validated['certificationText'])) {
            return response()->json(['success' => false, 'errors' => ['certificationText' => ['Please specify the other certification.']]], 422);
        }

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $form = PreMpdrForm::where('no', $no_reg)->firstOrFail(); // Gunakan $no_reg dari parameter route

            // [PERBAIKAN] Pastikan hanya user yang berhak yang bisa update DRAFT
             if ($form->user_id !== $user->id || $form->status !== 'Draft') {
                 throw new \Exception('Unauthorized to update this form.');
             }

            $validatedData = $validator->validated(); // Ambil data yang sudah divalidasi

            // Tentukan status dan route_to
            $newStatus = $validatedData['form_status'] == 'Submit' ? 'In Approval' : $validatedData['form_status'];
            $routeTo = null;
            $supervisor = $user->atasan;
            $validator_nik = $supervisor?->nik;
            if ($newStatus == 'In Approval') {
                 if (!$validator_nik) {
                     throw new \Exception('Supervisor not found. Cannot submit.');
                 }
                 $routeTo = $supervisor?->position?->name ?? $supervisor?->name;
            }

            // Update form utama
            $form->update([
                'project_name' => $validatedData['projectName'],
                'brand_name' => $validatedData['brandName'],
                'level_priority' => $validatedData['levelPriority'],
                'status' => $newStatus,
                'route_to' => $routeTo,
            ]);

            // Update relasi (gunakan $validatedData)
            $form->detail()->update([
                'rational_for_development' => $validatedData['rationalForDevelopment'],
                'target_launch' => $validatedData['targetLaunchText']
            ]);
            $form->category()->update([
                'category' => $validatedData['productCategory'],
                'other' => $validatedData['productCategory'] == 'Others' ? $validatedData['productCategoryText'] : null
            ]);
            $form->channel()->update([
                'category' => implode(', ', $validatedData['channel']),
                'country' => in_array('International', $validatedData['channel'], true) ? $validatedData['country'] : null
            ]);
            $form->description()->update(
                [
                'product_description' => $validatedData['productDescription'],
                'usage_description' => $validatedData['usageDescription'],
                'storage_temperature' => $validatedData['storageTemperature'],
                'delivery_temperature' => $validatedData['deliveryTemperature']
                ]
            );
            $form->certification()->update([
                'category' => implode(', ', $validatedData['certification']),
                'other' => in_array('Others', $validatedData['certification'], true) ? $validatedData['certificationText'] : null
            ]);

            $form->competitor()->delete();

            if ($request->has('competitor_names')) {
                foreach ($validatedData['competitor_names'] as $index => $name) {
                    if (!empty($name)) {
                        $form->competitor()->create([
                            'form_id'   => $form->id,
                            'name'      => $name,
                            'size'      => $validatedData['competitor_sizes'][$index] ?? null,
                            'packaging' => $validatedData['competitor_packagings'][$index] ?? null,
                            'price'     => $validatedData['competitor_prices'][$index] ?? null,
                        ]);
                    }
                }
            }
            $form->packaging()->update([
                'weight' => $validatedData['weightProduct'],
                'category' => $validatedData['packaging'],
                'detail' => $validatedData[$validatedData['packaging'].'PackagingText'],
                'product_variation' => $validatedData['productVariation']
            ]);
            $form->market()->update([
                'potential_volume' => $validatedData['potentialVolume'],
                'expected_margin' => $validatedData['expectedMargin'],
                'price_estimate' => $validatedData['priceEstimate']
            ]);


            if ($newStatus == 'In Approval' && $request->form_status == 'Submit') {
                 $firstApproverDetail = $form->approvedDetail()->where('level', 1)->first();
                 $supervisorName = User::where('nik', $validator_nik)->value('name');
                 if ($firstApproverDetail) {
                     $firstApproverDetail->update([
                         'approver' => $validator_nik,
                         'name' => $supervisorName,
                         'approver_position' => $supervisor?->position?->name,
                         'token' => Str::uuid(),
                     ]);
                 } else {
                     $form->approvedDetail()->create([
                         'approver' => $validator_nik,
                         'name' => $supervisorName,
                         'level' => 1,
                         'token' => Str::uuid(),
                     ]);
                 }

                 // Hapus approver lama (level > 1) jika ada, lalu tambahkan yang baru
                 $form->approvedDetail()->where('level', '>', 1)->delete();
                  $approvers = PreMpdrApprover::where('user_nik', $user->nik)->orderBy('level', 'asc')->get();
                  if ($approvers->isEmpty()) {
                      $firstUserNik = PreMpdrApprover::value('user_nik');
                      if ($firstUserNik) {
                          $approvers = PreMpdrApprover::where('user_nik', $firstUserNik)->orderBy('level', 'asc')->get();
                      }
                  }

                  foreach ($approvers as $approver) {
                      $form->approvedDetail()->create([
                          'form_id' => $form->id,
                          'approver' => $approver->approver_nik,
                          'name' => $approver->approver_name,
                          'approver_position' => User::with('position')->where('nik', $approver->approver_nik)->first()?->position?->name,
                          'level' => $approver->level,
                          'token' => Str::uuid()
                      ]);
                  }

                  $this->sendMailToApprover($form->no); // Kirim email
            }

            activity()
                ->performedOn($form)
                ->inLog('prempdr')
                ->event('Update')
                ->causedBy($user)
                ->withProperties(['no' => $form->no, 'action' => 'updated'])
                ->log('Update PreMpdr Form ' . $form->no . ' by ' . $user->name);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Form successfully updated!']);

        } catch (\Illuminate\Validation\ValidationException $e) { // Tangkap ValidationException
             DB::rollback();
             return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollback();
            // Tampilkan pesan error yang lebih spesifik jika memungkinkan
            return response()->json(['success' => false, 'message' => 'Error updating form: ' . $e->getMessage()], 500);
        }
    }

    private function normalizeMultiSelectInputs(Request $request): void
    {
        foreach (['channel', 'certification'] as $field) {
            $value = $request->input($field);
            if (is_string($value)) {
                $request->merge([$field => array_filter(array_map('trim', explode(',', $value)))]);
            }
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
            $form = PreMpdrForm::where('no', $no)
                    ->where('status', 'Draft')
                    ->first();
            $form->delete();
            DB::commit();

            activity()
            ->performedOn($form)
            ->inLog('prempdr')
            ->event('Delete')
            ->causedBy($user)
            ->withProperties(['no' => $no, 'action' => 'deleted'])
            ->log('Delete PreMpdr Form ' . $no . ' by ' . $user->name . ' at ' . now());

            Alert::toast('Form successfully deleted!.', 'success');
            return redirect()->route('prempdr.index');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            Alert::toast('There was an error updating the form.'.$e->getMessage(), 'error');
            return back();
        }
    }

    public function report()
    {
        return view('page.pre-mpdr.report-prempdr');
    }

    public function approval()
    {
        return view('page.pre-mpdr.approval-prempdr');
    }

    public function log()
    {
        return view('page.pre-mpdr.log-prempdr');
    }

    public function printPdf($no_reg)
    {
        // 1. Ambil data form beserta semua relasinya
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
            'approvedDetail' => function ($query) {
                $query->orderBy('level', 'asc'); // Urutkan approver berdasarkan level
            }
        ])->where('no', $no_reg)->firstOrFail();

        // 2. Siapkan nama file PDF
        $fileName = 'Pre-MPDR-Report-' . $no_reg . '.pdf';

        // 3. Muat view dan passing data ke dalamnya
        $pdf = Pdf::loadView('page.pre-mpdr.print-prempdr', ['form' => $form]);

        // 4. Atur orientasi kertas menjadi landscape jika perlu
        $pdf->setPaper('a4', 'portrait');

        // 5. Kirim PDF ke browser untuk ditampilkan atau di-download
        return $pdf->stream($fileName);
    }

    // public function print($no_reg)
    // {
    //     return view('page.pre-mpdr.print-prempdr')->with('no_reg', $no_reg);
    // }

    public function noReg()
    {
        $year = date('y');
        $prefix = "{$year}PREMPDR";
        $lastPcr = PreMpdrForm::where('no', 'like', "{$prefix}%")->orderBy('no', 'desc')->first();

        if ($lastPcr) {
            $lastNo = (int)substr($lastPcr->no_reg, -4);
            $newNo = $lastNo + 1;
        } else {
            $newNo = 1;
        }

        do {
            $newNoReg = $prefix . str_pad($newNo, 4, '0', STR_PAD_LEFT);
            $existingPcr = PreMpdrForm::where('no', $newNoReg)->first();
            if ($existingPcr) {
                $newNo++;
            }
        } while ($existingPcr);

        return response()->json(['no_reg' => $newNoReg]);
    }

    public function getFormData(Request $request)
    {
        $form = PreMpdrForm::with('revision', 'detail', 'category', 'channel', 'description', 'certification', 'competitor', 'packaging', 'market', 'approvedDetail')->where('no', $request->input('no_reg'))->first();
        if($form){
            return response()->json($form);
        }

        return response()->json("No form found.");
    }

    public function getReportData()
    {
        $form = PreMpdrForm::with('revision', 'detail', 'category', 'channel', 'description', 'certification', 'competitor', 'packaging', 'market', 'approvedDetail')->whereIn('status', ['Approved', 'Rejected'])->orderBy('created_at', 'desc')->get();
        if($form){
            $form = $form->map(function($item) {
                /** @var PreMpdrForm $item */
                $item->new_created_at = $item->created_at->format('j F Y');
                return $item;
            });
            return response()->json($form);
        }

        return response()->json("No form found.");
    }

    public function template()
    {
        return view('page.pre-mpdr.template-form-prempdr');
    }

    public function showDraftForm($no_reg){
        confirmDelete();
        return view('page.pre-mpdr.draft-prempdr')->with('no_reg', $no_reg);
    }

    public function getFormList(Request $request)
    {
        $query = PreMpdrForm::with(['approvedDetail' => function ($q) {
            $q->select(DB::raw("form_id, count(*) as total, sum(case when status is not null then 1 else 0 end) as approved_count"))->groupBy('form_id');
        }]);

        if ($request->has('searchBuilder')) {
            $this->applySearchBuilder($query, $request->input('searchBuilder'));
        }

        $forms = $query->orderBy('created_at', 'desc')->get();

        if ($forms) {
            $pendingApprovers = PreMpdrApprovedDetail::with('user.position')
                ->whereIn('form_id', $forms->pluck('id'))
                ->whereNull('status')
                ->orderBy('level')
                ->orderBy('id')
                ->get()
                ->groupBy('form_id');

            $forms->each(function (PreMpdrForm $form) use ($pendingApprovers) {
                if ($form->status !== 'In Approval') {
                    return;
                }

                $currentApprover = $pendingApprovers->get($form->id)?->first();
                if ($currentApprover) {
                    $form->route_to = $currentApprover->approver_position
                        ?: $currentApprover->user?->position?->name
                        ?: $form->route_to;
                }
            });

            return response()->json($forms);
        }

        return response()->json([]);
    }

    // public function getPrintData(Request $request)
    // {
    //     $form = PreMpdrForm::with('revision', 'detail', 'category', 'channel', 'description', 'certification', 'competitor', 'packaging', 'market', 'approvedDetail')->where('no', $request->input('no_reg'))->first();
    //     if($form){
    //         return response()->json($form);
    //     }

    //     return response()->json("Tidak ada Form");
    // }

    private function sendMailToApprover($no_reg)
    {
        $form = PreMpdrForm::with('revision', 'detail', 'category', 'channel', 'description', 'certification', 'competitor', 'packaging', 'market', 'approvedDetail')
        ->where('no', $no_reg)->first();
        if ($form && $form->status === 'In Approval') {
            $approved_detail = $form->approvedDetail()
                ->whereNull('status')
                ->orderBy('level')
                ->orderBy('id')
                ->first();

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

    public function showDetail($formNo)
    {
        // Ambil data form beserta semua relasinya
            $formData = PreMpdrForm::with(
                'revision', 'detail', 'category', 'channel', 'description',
                'certification', 'competitor', 'packaging', 'market',
                'user.position'
            )->where('no', $formNo)->firstOrFail();

            // Ambil approvedDetail terurut menurut level
            $approvedDetails = $formData->approvedDetail()->orderBy('level', 'asc')->get();

            // Hitung progress
            $totalApprovers = $approvedDetails->count();
            $processedCount = $approvedDetails->filter(function ($d) {
                return $d->status !== null && $d->status !== 'pending' && $d->status !== 'Vacant';
            })->count();

            // Susun tracker steps
            $steps = [];
            // Step: Submitted
            $steps[] = [
                'key' => 'submitted',
                'label' => 'Submitted by ' . ($formData->user?->name ?? 'Unknown user'),
                'status' => $formData->created_at ? 'completed' : 'pending',
                'date' => $formData->created_at ? $formData->created_at->toDateTimeString() : null,
                'details' => null,
            ];

            foreach ($approvedDetails as $detail) {
                $steps[] = [
                    'key' => 'approver_' . $detail->id,
                    'label' => $this->approverPositionLabel($detail),
                    'level' => $detail->level,
                    'status' => $detail->status ?? 'pending',
                    'comment' => $detail->comment ?? null,
                    'date' => $detail->approved_date ? (is_string($detail->approved_date) ? Carbon::parse($detail->approved_date)->toDateTimeString() : $detail->approved_date->toDateTimeString()) : null,
                    'nik' => $detail->approver,
                ];
            }

            // Step: Completed/Final
            if (in_array($formData->status, ['Approved', 'Rejected'])) {
                $steps[] = [
                    'key' => 'final',
                    'label' => 'Completed',
                    'status' => $formData->status === 'Approved' ? 'completed' : 'rejected',
                    'date' => $formData->updated_at ? $formData->updated_at->toDateTimeString() : null,
                    'details' => null,
                ];
            }

            // Ambil activity log (history) untuk form ini
            $activities = SpatieActivity::where('subject_type', PreMpdrForm::class)
                ->where('subject_id', $formData->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $history = $activities->map(function ($act) {
                return [
                    'id' => $act->id,
                    'actor' => $act->causer ? ($act->causer->name ?? $act->causer->id) : 'System',
                    'event' => $act->description ?? $act->event ?? null,
                    'properties' => $act->properties ?? [],
                    'time' => $act->created_at->toDateTimeString(),
                ];
            })->values();

            // Gabungkan data form (array) dengan tracker + history
            $response = $formData->toArray();
            $response['approved_detail_ordered'] = $approvedDetails->toArray();
            $response['approval_tracker'] = [
                'steps' => $steps,
                'total_approvers' => $totalApprovers,
                'processed_count' => $processedCount,
                'progress_percent' => $totalApprovers > 0 ? round(($processedCount / $totalApprovers) * 100) : 0,
                'current_status' => $formData->status,
            ];
            $response['history'] = $history;

            return response()->json($response);
    }
}
