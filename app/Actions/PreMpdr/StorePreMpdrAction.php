<?php

namespace App\Actions\PreMpdr;

use App\Jobs\PreMpdr\ProcessApproval;
use App\Models\PREMPDR\PreMpdrApprover;
use App\Models\PREMPDR\PreMpdrForm;
use App\Models\PREMPDR\PreMpdrRevision;
use App\Models\User;
use App\Notifications\MpdrNotification;
use App\Services\PreMpdr\PreMpdrApprovalService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StorePreMpdrAction
{
    public function __construct(
        protected PreMpdrApprovalService $approvalService
    ) {}

    /**
     * Execute the store action for Pre-MPDR form.
     *
     * @param array $data Validated input data
     * @param User $user Current authenticated user
     * @return PreMpdrForm
     * @throws \Exception
     */
    public function execute(array $data, User $user): PreMpdrForm
    {
        DB::beginTransaction();

        try {
            $supervisor = $user->atasan;
            $revision = PreMpdrRevision::latest()->firstOrFail();

            if ($data['form_status'] === 'Submit' && !$supervisor?->nik) {
                throw new \Exception('A supervisor could not be found for this user. The form cannot be submitted.');
            }

            // Create main PreMpdrForm record
            $form = PreMpdrForm::create([
                'no' => $data['no_reg'],
                'user_id' => $user->id,
                'revision_id' => $revision->id,
                'project_name' => $data['projectName'],
                'brand_name' => $data['brandName'],
                'level_priority' => $data['levelPriority'],
                'status' => $data['form_status'] === 'Submit' ? 'In Approval' : 'Draft',
                'route_to' => $data['form_status'] === 'Submit' ? ($supervisor->position->name ?? $supervisor->name) : null,
            ]);

            // Save details
            $form->detail()->create([
                'rational_for_development' => $data['rationalForDevelopment'],
                'target_launch' => $data['targetLaunchText'],
            ]);

            $form->category()->create([
                'category' => $data['productCategory'],
                'other' => $data['productCategoryText'] ?? null,
            ]);

            $channels = is_array($data['channel']) ? $data['channel'] : explode(',', $data['channel']);
            $form->channel()->create([
                'category' => implode(', ', $channels),
                'country' => in_array('International', $channels, true) ? ($data['country'] ?? null) : null,
            ]);

            $form->description()->create([
                'product_description' => $data['productDescription'],
                'usage_description' => $data['usageDescription'],
                'storage_temperature' => $data['storageTemperature'],
                'delivery_temperature' => $data['deliveryTemperature'],
            ]);

            $certifications = is_array($data['certification']) ? $data['certification'] : explode(',', $data['certification']);
            $form->certification()->create([
                'category' => implode(', ', $certifications),
                'other' => in_array('Others', $certifications, true) ? ($data['certificationText'] ?? null) : null,
            ]);

            if (!empty($data['competitor_names']) && is_array($data['competitor_names'])) {
                foreach ($data['competitor_names'] as $index => $name) {
                    if (!empty($name)) {
                        $form->competitor()->create([
                            'name'      => $name,
                            'size'      => $data['competitor_sizes'][$index] ?? null,
                            'packaging' => $data['competitor_packagings'][$index] ?? null,
                            'price'     => $data['competitor_prices'][$index] ?? null,
                        ]);
                    }
                }
            }

            $form->packaging()->create([
                'weight' => $data['weightProduct'],
                'category' => $data['packaging'],
                'detail' => $data[$data['packaging'] . 'PackagingText'] ?? null,
                'product_variation' => $data['productVariation'],
            ]);

            $form->market()->create([
                'potential_volume' => $data['potentialVolume'],
                'expected_margin' => $data['expectedMargin'],
                'price_estimate' => $data['priceEstimate'],
            ]);

            // Setup approval workflow if submitted
            if ($data['form_status'] === 'Submit') {
                $isSupervisorVacant = strtolower(trim($supervisor->status ?? '')) === 'vacant';
                $form->approvedDetail()->create([
                    'approver' => $supervisor->nik,
                    'name' => $supervisor->name,
                    'approver_position' => $supervisor->position->name ?? null,
                    'level' => 1,
                    'status' => $isSupervisorVacant ? 'Vacant' : null,
                    'token' => $isSupervisorVacant ? null : Str::uuid(),
                ]);

                $masterApprovers = PreMpdrApprover::where('user_nik', $user->nik)->orderBy('level', 'asc')->get();
                if ($masterApprovers->isEmpty()) {
                    $firstUserNik = PreMpdrApprover::value('user_nik');
                    if ($firstUserNik) {
                        $masterApprovers = PreMpdrApprover::where('user_nik', $firstUserNik)->orderBy('level', 'asc')->get();
                    }
                }

                foreach ($masterApprovers as $approver) {
                    $approverUser = User::with('position')->where('nik', $approver->approver_nik)->first();
                    $isVacant = strtolower(trim($approver->approver_status ?? '')) === 'vacant'
                        || strtolower(trim($approverUser?->status ?? '')) === 'vacant';

                    $form->approvedDetail()->create([
                        'approver' => $approver->approver_nik,
                        'name' => $approver->approver_name,
                        'approver_position' => $approverUser?->position?->name,
                        'level' => $approver->level,
                        'status' => $isVacant ? 'Vacant' : null,
                        'token' => $isVacant ? null : Str::uuid(),
                    ]);
                }
            }

            // Spatie Activity Log
            activity()
                ->performedOn($form)
                ->inLog('prempdr')
                ->event('Create')
                ->causedBy($user)
                ->withProperties(['no' => $form->no, 'action' => 'created'])
                ->log('Created Pre-MPDR form ' . $form->no);

            DB::commit();

            // Dispatch notification & email to first active approver (skips vacant)
            if ($data['form_status'] === 'Submit') {
                $this->approvalService->sendMailToApprover($form->no);
            }

            return $form;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
