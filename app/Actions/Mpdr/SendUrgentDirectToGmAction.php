<?php

namespace App\Actions\Mpdr;

use App\Jobs\Mpdr\ProcessApproval;
use App\Models\MPDR\MpdrForm;
use App\Models\User;
use App\Notifications\MpdrNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SendUrgentDirectToGmAction
{
    /**
     * Send direct urgent approval email to General Manager.
     *
     * @param string $noReg Form registration number
     * @param User $causer User requesting the urgent dispatch
     * @return User The GM user
     * @throws \Exception
     */
    public function execute(string $noReg, User $causer): User
    {
        DB::beginTransaction();

        try {
            $form = MpdrForm::with('approvedDetail.user')
                ->where('no', $noReg)
                ->lockForUpdate()
                ->firstOrFail();

            if ($form->status !== 'In Approval') {
                throw new \Exception('Email can only be sent for forms that are "In Approval".');
            }

            if (
                $form->approvedDetail->contains(function ($detail) {
                    return strtolower(trim($detail->status ?? '')) === 'not approve';
                }) || strtolower(trim($form->initiatorDetail?->status ?? '')) === 'not approve'
            ) {
                throw new \Exception('Direct to GM cannot be sent because an approval has been rejected.');
            }

            $gmDetail = $form->approvedDetail->first(function ($detail) {
                $gmUser = User::where('nik', $detail->approver_nik)->first();
                return $gmUser && $gmUser->hasRole('gm');
            });
            $gmUser = $gmDetail ? User::where('nik', $gmDetail->approver_nik)->first() : null;

            if (!$gmUser || !$gmDetail) {
                throw new \Exception('General Manager approver not found for this form.');
            }

            if ($gmDetail->status !== 'pending') {
                throw new \Exception('The General Manager has already processed this form.');
            }

            $form->gm_direct_approval = true;
            $form->save();

            $gmDetail->token = (string) Str::uuid();
            $gmDetail->save();

            activity()
                ->performedOn($form)
                ->inLog('mpdr')
                ->event('DirectToGM')
                ->causedBy($causer)
                ->withProperties(['no' => $noReg, 'approver' => $gmUser->name])
                ->log('Direct approval email sent to GM for MPDR ' . $noReg);

            DB::commit();

            ProcessApproval::dispatch($gmUser, $form, $gmDetail)->delay(now()->addSeconds(2));

            $data = [
                'title' => 'Urgent Approval Needed',
                'message' => 'Document ' . $form->no . ' was sent directly to the GM for approval.',
                'form_no' => $form->no,
            ];
            $gmUser->notify(new MpdrNotification($data, 'approval_request'));

            return $gmUser;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
