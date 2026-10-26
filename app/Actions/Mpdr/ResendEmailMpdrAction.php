<?php

namespace App\Actions\Mpdr;

use App\Jobs\Mpdr\ProcessApproval;
use App\Models\MPDR\MpdrForm;
use App\Models\User;
use App\Notifications\MpdrNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ResendEmailMpdrAction
{
    /**
     * Resend notification email to a pending MPDR approver.
     *
     * @param string $noReg Form registration number
     * @param string $approverNik Approver NIK
     * @param User $causer User requesting the resend
     * @return User
     * @throws \Exception
     */
    public function execute(string $noReg, string $approverNik, User $causer): User
    {
        DB::beginTransaction();

        try {
            $form = MpdrForm::where('no', $noReg)->lockForUpdate()->first();
            if (!$form) {
                throw new \Exception('MPDR form not found.');
            }

            if ($form->status !== 'In Approval') {
                throw new \Exception('Email can only be resent for forms with In Approval status.');
            }

            $currentApprovedDetail = $form->approvedDetail()
                ->with('user.roles')
                ->where('status', 'pending')
                ->where('approver_nik', $approverNik)
                ->lockForUpdate()
                ->first();

            if (!$currentApprovedDetail) {
                throw new \Exception('No pending approver was found.');
            }

            $approverUser = $currentApprovedDetail->user ?: User::where('nik', $currentApprovedDetail->approver_nik)->first();

            if (!$approverUser) {
                throw new \Exception('User data for approver (' . $currentApprovedDetail->approver_name . ') was not found.');
            }

            if ($approverUser->hasRole(['gm', 'GM']) && !$form->isGmEligibleForApproval()) {
                throw new \Exception('The GM email can only be sent after all approvers meet the requirements or the form is sent directly to the GM.');
            }

            $currentApprovedDetail->token = (string) Str::uuid();
            $currentApprovedDetail->save();

            activity()
                ->performedOn($form)
                ->inLog('mpdr')
                ->event('ResendEmail')
                ->causedBy($causer)
                ->withProperties(['no' => $noReg, 'approver' => $approverUser->name])
                ->log('Resent approval email to ' . $approverUser->name . ' for MPDR ' . $noReg);

            DB::commit();

            ProcessApproval::dispatch($approverUser, $form, $currentApprovedDetail)->delay(now()->addSeconds(2));

            $notificationData = [
                'title' => 'Reminder: MPDR Approval Request',
                'message' => 'MPDR request ' . $form->no . ' requires your approval (reminder email resent).',
                'form_no' => $form->no,
            ];
            $approverUser->notify(new MpdrNotification($notificationData, 'approval_request'));

            return $approverUser;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
