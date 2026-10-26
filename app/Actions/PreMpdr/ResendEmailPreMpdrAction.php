<?php

namespace App\Actions\PreMpdr;

use App\Jobs\PreMpdr\ProcessApproval;
use App\Models\PREMPDR\PreMpdrForm;
use App\Models\User;
use App\Notifications\MpdrNotification;
use App\Services\PreMpdr\PreMpdrApprovalService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ResendEmailPreMpdrAction
{
    public function __construct(
        protected PreMpdrApprovalService $approvalService
    ) {}

    /**
     * Resend notification email to the pending approver.
     *
     * @param string $noReg Form registration number
     * @param User $causer User requesting the resend
     * @return User The approver user
     * @throws \Exception
     */
    public function execute(string $noReg, User $causer): User
    {
        DB::beginTransaction();

        try {
            $form = PreMpdrForm::where('no', $noReg)->lockForUpdate()->first();
            if (!$form) {
                throw new \Exception('Pre-MPDR form not found.');
            }

            if ($form->status !== 'In Approval') {
                throw new \Exception('Email can only be resent for forms with In Approval status.');
            }

            $currentApprovedDetail = $form->approvedDetail()
                ->with('user.position')
                ->whereNull('status')
                ->orderBy('level', 'asc')
                ->orderBy('id', 'asc')
                ->lockForUpdate()
                ->first();

            if (!$currentApprovedDetail) {
                throw new \Exception('There are no active approvers awaiting approval.');
            }

            $newToken = (string) Str::uuid();
            $currentApprovedDetail->token = $newToken;
            $currentApprovedDetail->save();

            $approverUser = $currentApprovedDetail->user ?: User::where('nik', $currentApprovedDetail->approver)->first();

            if (!$approverUser) {
                throw new \Exception('User data for approver (' . $currentApprovedDetail->name . ') was not found.');
            }

            $form->route_to = $this->approvalService->approverPositionLabel($currentApprovedDetail);
            $form->save();

            activity()
                ->performedOn($form)
                ->inLog('prempdr')
                ->event('ResendEmail')
                ->causedBy($causer)
                ->withProperties(['no' => $noReg, 'approver' => $approverUser->name])
                ->log('Resent approval email to ' . $approverUser->name . ' for Pre-MPDR ' . $noReg);

            DB::commit();

            ProcessApproval::dispatch($approverUser, $form, $currentApprovedDetail)->delay(now()->addSeconds(2));

            $notificationData = [
                'title' => 'Reminder: Pre-MPDR Approval Request',
                'message' => 'Pre-MPDR request ' . $form->no . ' requires your approval (reminder email resent).',
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
