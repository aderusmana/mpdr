<?php

namespace App\Actions\PreMpdr;

use App\Models\PREMPDR\PreMpdrApprover;
use App\Models\PREMPDR\PreMpdrForm;
use App\Models\User;
use App\Services\PreMpdr\PreMpdrService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdatePreMpdrAction
{
    public function __construct(
        protected PreMpdrService $preMpdrService
    ) {}

    /**
     * Execute the update action for Pre-MPDR form.
     *
     * @param string $noReg Form registration number
     * @param array $data Validated input data
     * @param User $user Current authenticated user
     * @return PreMpdrForm
     * @throws \Exception
     */
    public function execute(string $noReg, array $data, User $user): PreMpdrForm
    {
        DB::beginTransaction();

        try {
            $form = PreMpdrForm::where('no', $noReg)->firstOrFail();

            if ($form->user_id !== $user->id || $form->status !== 'Draft') {
                throw new \Exception('Unauthorized to update this form.');
            }

            $newStatus = $data['form_status'] === 'Submit' ? 'In Approval' : $data['form_status'];
            $routeTo = null;
            $supervisor = $user->atasan;
            $validatorNik = $supervisor?->nik;

            if ($newStatus === 'In Approval') {
                if (!$validatorNik) {
                    throw new \Exception('Supervisor not found. Cannot submit.');
                }
                $routeTo = $supervisor?->position?->name ?? $supervisor?->name;
            }

            // Update main form record
            $form->update([
                'project_name' => $data['projectName'],
                'brand_name' => $data['brandName'],
                'level_priority' => $data['levelPriority'],
                'status' => $newStatus,
                'route_to' => $routeTo,
            ]);

            // Update related records
            $form->detail()->update([
                'rational_for_development' => $data['rationalForDevelopment'],
                'target_launch' => $data['targetLaunchText'],
            ]);

            $form->category()->update([
                'category' => $data['productCategory'],
                'other' => $data['productCategory'] === 'Others' ? ($data['productCategoryText'] ?? null) : null,
            ]);

            $channels = is_array($data['channel']) ? $data['channel'] : explode(',', $data['channel']);
            $form->channel()->update([
                'category' => implode(', ', $channels),
                'country' => in_array('International', $channels, true) ? ($data['country'] ?? null) : null,
            ]);

            $form->description()->update([
                'product_description' => $data['productDescription'],
                'usage_description' => $data['usageDescription'],
                'storage_temperature' => $data['storageTemperature'],
                'delivery_temperature' => $data['deliveryTemperature'],
            ]);

            $certifications = is_array($data['certification']) ? $data['certification'] : explode(',', $data['certification']);
            $form->certification()->update([
                'category' => implode(', ', $certifications),
                'other' => in_array('Others', $certifications, true) ? ($data['certificationText'] ?? null) : null,
            ]);

            // Recreate competitors
            $form->competitor()->delete();
            if (!empty($data['competitor_names']) && is_array($data['competitor_names'])) {
                foreach ($data['competitor_names'] as $index => $name) {
                    if (!empty($name)) {
                        $form->competitor()->create([
                            'form_id'   => $form->id,
                            'name'      => $name,
                            'size'      => $data['competitor_sizes'][$index] ?? null,
                            'packaging' => $data['competitor_packagings'][$index] ?? null,
                            'price'     => $data['competitor_prices'][$index] ?? null,
                        ]);
                    }
                }
            }

            $form->packaging()->update([
                'weight' => $data['weightProduct'],
                'category' => $data['packaging'],
                'detail' => $data[$data['packaging'] . 'PackagingText'] ?? null,
                'product_variation' => $data['productVariation'],
            ]);

            $form->market()->update([
                'potential_volume' => $data['potentialVolume'],
                'expected_margin' => $data['expectedMargin'],
                'price_estimate' => $data['priceEstimate'],
            ]);

            // Handle approval workflow transitions
            if ($newStatus === 'In Approval' && $data['form_status'] === 'Submit') {
                $firstApproverDetail = $form->approvedDetail()->where('level', 1)->first();
                $supervisorName = User::where('nik', $validatorNik)->value('name');

                if ($firstApproverDetail) {
                    $firstApproverDetail->update([
                        'approver' => $validatorNik,
                        'name' => $supervisorName,
                        'approver_position' => $supervisor?->position?->name,
                        'token' => Str::uuid(),
                    ]);
                } else {
                    $form->approvedDetail()->create([
                        'approver' => $validatorNik,
                        'name' => $supervisorName,
                        'approver_position' => $supervisor?->position?->name,
                        'level' => 1,
                        'token' => Str::uuid(),
                    ]);
                }

                // Delete old higher-level approvers and recreate from master
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
                        'token' => Str::uuid(),
                    ]);
                }
            }

            // Spatie Activity Log
            activity()
                ->performedOn($form)
                ->inLog('prempdr')
                ->event('Update')
                ->causedBy($user)
                ->withProperties(['no' => $form->no, 'action' => 'updated'])
                ->log('Update PreMpdr Form ' . $form->no . ' by ' . $user->name);

            DB::commit();

            if ($newStatus === 'In Approval' && $data['form_status'] === 'Submit') {
                $this->preMpdrService->sendMailToApprover($form->no);
            }

            return $form;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
