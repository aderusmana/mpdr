<?php

namespace App\Actions\Mpdr;

use App\Jobs\Mpdr\ProcessApproval;
use App\Models\MPDR\MpdrApprover;
use App\Models\MPDR\MpdrForm;
use App\Models\User;
use App\Notifications\MpdrNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateMpdrAction
{
    /**
     * Execute the update action for MPDR form.
     *
     * @param string $noReg Form registration number
     * @param array $data Validated input data
     * @param User $user Current authenticated user
     * @return MpdrForm
     * @throws \Exception
     */
    public function execute(string $noReg, array $data, User $user): MpdrForm
    {
        DB::beginTransaction();

        try {
            $form = MpdrForm::where('no', $data['no_reg'] ?? $noReg)
                ->where('user_id', $user->id)
                ->firstOrFail();

            // Determine requester user (prefer requester_id if provided)
            $requesterUser = null;
            if (!empty($data['requester_id'])) {
                $requesterUser = User::find($data['requester_id']);
            }
            if (!$requesterUser && !empty($data['initiator'])) {
                $requesterUser = User::where('nik', $data['initiator'])->first();
            }

            $initiatorName = $requesterUser
                ? $requesterUser->name
                : ($form->initiator ?? $user->name);

            $form->update([
                'product_name' => $data['productName'] ?? $form->product_name,
                'level_priority' => $data['levelPriority'] ?? $form->level_priority,
                'initiator' => $initiatorName,
                'status' => $data['form_status'] === 'Submit' ? 'In Approval' : $data['form_status'],
                'type_of_request' => $data['typeOfRequest'] ?? $form->type_of_request,
                'requester_id' => $data['requester_id'] ?? $form->requester_id,
                'gm_direct_approval' => false,
            ]);

            // Update related details
            $form->detail()->update([
                'rational_for_development' => $data['rationalForDevelopment'],
                'target_launch' => $data['targetLaunchText'],
            ]);

            $form->category()->update([
                'category' => $data['productCategory'],
                'other' => $data['productCategoryText'] ?? null,
            ]);

            $channels = is_array($data['channel']) ? $data['channel'] : explode(',', $data['channel']);
            $form->channel()->updateOrCreate(
                ['form_id' => $form->id],
                [
                    'category' => implode(', ', $channels),
                    'country' => in_array('International', $channels, true) ? ($data['country'] ?? null) : null,
                ]
            );

            $form->description()->update([
                'product_description' => $data['productDescription'],
                'usage_description' => $data['usageDescription'],
                'storage_temperature' => $data['storageTemperature'],
                'delivery_temperature' => $data['deliveryTemperature'],
            ]);

            $certifications = is_array($data['certification']) ? $data['certification'] : explode(',', $data['certification']);
            $form->certification()->updateOrCreate(
                ['form_id' => $form->id],
                [
                    'category' => implode(', ', $certifications),
                    'other' => in_array('Others', $certifications, true) ? ($data['certificationText'] ?? null) : null,
                ]
            );

            // Recreate competitors
            $form->competitor()->delete();
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

            $form->initiatorDetail()->update([
                'initiator_nik' => $requesterUser ? $requesterUser->nik : ($data['initiator'] ?? null),
                'initiator_name' => $requesterUser ? $requesterUser->name : (User::where('nik', $data['initiator'] ?? '')->value('name') ?? null),
                'status' => 'pending',
                'token' => Str::uuid(),
            ]);

            $sendApproverNotifications = false;

            if ($data['form_status'] === 'Submit') {
                $approvers = MpdrApprover::where('user_nik', $user->nik)->get();
                if ($approvers->isEmpty()) {
                    $firstUserNik = MpdrApprover::value('user_nik');
                    if ($firstUserNik) {
                        $approvers = MpdrApprover::where('user_nik', $firstUserNik)->get();
                    }
                }

                foreach ($approvers as $approver) {
                    $form->approvedDetail()->create([
                        'form_id' => $form->id,
                        'approver_nik' => $approver->approver_nik,
                        'approver_name' => $approver->approver_name,
                        'status' => $approver->approver_status === 'Active' ? 'pending' : 'Vacant',
                        'token' => $approver->approver_status === 'Active' ? Str::uuid() : null,
                    ]);
                }

                $gm = User::role('gm')->first();
                if ($gm && !$form->approvedDetail()->where('approver_nik', $gm->nik)->exists()) {
                    $form->approvedDetail()->create([
                        'form_id' => $form->id,
                        'approver_nik' => $gm->nik,
                        'approver_name' => $gm->name,
                        'status' => 'pending',
                        'token' => Str::uuid(),
                    ]);
                }

                // Mark initiator as Vacant so approvers can act directly
                $form->initiatorDetail()->update([
                    'status' => 'Vacant',
                    'token' => null,
                ]);

                $sendApproverNotifications = true;
            }

            // Spatie Activity Log
            activity()
                ->performedOn($form)
                ->inLog('mpdr')
                ->event('Update')
                ->causedBy($user)
                ->withProperties(['no' => $data['no_reg'], 'action' => 'updated'])
                ->log('Update Mpdr Form ' . $data['no_reg'] . ' by ' . $user->name . ' at ' . now());

            if ($form->status === 'In Approval') {
                activity()
                    ->performedOn($form)
                    ->inLog('mpdr')
                    ->event('Submit')
                    ->causedBy($user)
                    ->withProperties(['no' => $data['no_reg'], 'action' => 'submitted'])
                    ->log('Submit Mpdr Form ' . $data['no_reg'] . ' by ' . $user->name . ' at ' . now());
            }

            DB::commit();

            // After commit, dispatch approver notifications (except GM)
            if ($sendApproverNotifications) {
                $formWithDetails = MpdrForm::with('approvedDetail')->find($form->id);
                if ($formWithDetails) {
                    foreach ($formWithDetails->approvedDetail as $detail) {
                        $approverUser = User::where('nik', $detail->approver_nik)->first();
                        if ($approverUser && !$approverUser->hasRole(['gm', 'GM']) && $detail->status === 'pending') {
                            ProcessApproval::dispatch($approverUser, $formWithDetails, $detail)->delay(10);

                            $notifData = [
                                'title' => 'New MPDR for Approval',
                                'message' => $form->no . ' needs your approval.',
                                'user_id' => $form->user_id,
                            ];
                            $approverUser->notify(new MpdrNotification($notifData, 'approval_request'));
                        }
                    }
                }
            }

            return $form;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
