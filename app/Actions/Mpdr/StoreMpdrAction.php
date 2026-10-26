<?php

namespace App\Actions\Mpdr;

use App\Jobs\Mpdr\ProcessApproval;
use App\Models\MPDR\MpdrApprover;
use App\Models\MPDR\MpdrForm;
use App\Models\MPDR\MpdrRevision;
use App\Models\User;
use App\Notifications\MpdrNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoreMpdrAction
{
    /**
     * Execute the store action for MPDR form.
     *
     * @param array $data Validated input data
     * @param User $user Current authenticated user
     * @return MpdrForm
     * @throws \Exception
     */
    public function execute(array $data, User $user): MpdrForm
    {
        DB::beginTransaction();

        try {
            $revision = MpdrRevision::latest()->firstOrFail();

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
                : (User::where('nik', $data['initiator'] ?? '')->value('name') ?? $user->name);

            $form = MpdrForm::create([
                'no' => $data['no_reg'],
                'user_id' => $user->id,
                'revision_id' => $revision->id,
                'product_name' => $data['productName'],
                'type_of_request' => $data['typeOfRequest'] ?? null,
                'requester_id' => $data['requester_id'] ?? null,
                'initiator' => $initiatorName,
                'level_priority' => $data['levelPriority'],
                'status' => $data['form_status'] === 'Submit' ? 'In Approval' : $data['form_status'],
                'prempdr_no' => $data['no_pre'],
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
                        $price = $data['competitor_prices'][$index] ?? '0';
                        if ($price === null || trim((string) $price) === '') {
                            $price = '0';
                        }
                        $form->competitor()->create([
                            'name'      => $name,
                            'size'      => $data['competitor_sizes'][$index] ?? null,
                            'packaging' => $data['competitor_packagings'][$index] ?? null,
                            'price'     => $price,
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

            $form->initiatorDetail()->create([
                'initiator_nik' => $requesterUser ? $requesterUser->nik : ($data['initiator'] ?? null),
                'initiator_name' => $initiatorName,
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
                        'approver_nik' => $approver->approver_nik,
                        'approver_name' => $approver->approver_name,
                        'status' => $approver->approver_status === 'Active' ? 'pending' : 'Vacant',
                        'token' => $approver->approver_status === 'Active' ? Str::uuid() : null,
                    ]);
                }

                $gm = User::role('gm')->first();
                if ($gm && !$form->approvedDetail()->where('approver_nik', $gm->nik)->exists()) {
                    $form->approvedDetail()->create([
                        'approver_nik' => $gm->nik,
                        'approver_name' => $gm->name,
                        'status' => 'pending',
                        'token' => Str::uuid(),
                    ]);
                }

                // Since we route directly to approvers, mark initiator detail as Vacant
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
                ->event('Create')
                ->causedBy($user)
                ->withProperties(['no' => $data['no_reg'], 'action' => 'created'])
                ->log('Create Mpdr Form ' . $data['no_reg'] . ' by ' . $user->name . ' at ' . now());

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

            // After commit, dispatch approval emails/notifications
            if ($sendApproverNotifications) {
                $formWithDetails = MpdrForm::with('approvedDetail.user.roles')->find($form->id);
                if ($formWithDetails) {
                    foreach ($formWithDetails->approvedDetail as $detail) {
                        $approverUser = $detail->user ?: User::where('nik', $detail->approver_nik)->first();
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
