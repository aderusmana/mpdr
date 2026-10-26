<?php

namespace App\Actions\PreMpdr;

use App\Jobs\PreMpdr\ProcessApproval;
use App\Models\PREMPDR\PreMpdrForm;
use App\Models\User;
use App\Notifications\MpdrNotification;
use App\Services\PreMpdr\PreMpdrApprovalService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SendUrgentDirectToLastApproverPreMpdrAction
{
    public function __construct(
        protected PreMpdrApprovalService $approvalService
    ) {}

    /**
     * Send direct urgent approval email to Last Approver (e.g. Dept Head Sales & Marketing).
     *
     * @param string $noReg Form registration number
     * @param User $causer User requesting the urgent dispatch
     * @return User The last approver user
     * @throws \Exception
     */
    public function execute(string $noReg, User $causer): User
    {
        DB::beginTransaction();

        try {
            $form = PreMpdrForm::with(['approvedDetail.user', 'user'])
                ->where('no', $noReg)
                ->lockForUpdate()
                ->firstOrFail();

            if ($form->status !== 'In Approval') {
                throw new \Exception('Email can only be sent for forms that are "In Approval".');
            }

            if (
                $form->approvedDetail->contains(function ($detail) {
                    return strtolower(trim($detail->status ?? '')) === 'not approve';
                })
            ) {
                throw new \Exception('Direct to Last Approval cannot be sent because an approval has been rejected.');
            }

            $lastDetail = $form->approvedDetail->sortByDesc('level')->first();

            if (!$lastDetail) {
                throw new \Exception('Last approver detail not found for this form.');
            }

            $lastUser = $lastDetail->user ?: User::where('nik', $lastDetail->approver)->first();

            if (!$lastUser) {
                throw new \Exception('Last approver user account not found for this form.');
            }

            $lastStatus = strtolower(trim($lastDetail->status ?? ''));
            if ($lastStatus !== '' && $lastStatus !== 'pending') {
                throw new \Exception('The Last Approver has already processed this form.');
            }

            $form->last_approver_direct_approval = true;
            $form->route_to = $this->approvalService->approverPositionLabel($lastDetail);
            $form->save();

            $lastDetail->token = (string) Str::uuid();
            $lastDetail->save();

            $lastPosition = $this->approvalService->approverPositionLabel($lastDetail);

            activity()
                ->performedOn($form)
                ->inLog('prempdr')
                ->event('DirectToLastApprover')
                ->causedBy($causer)
                ->withProperties([
                    'no' => $noReg,
                    'approver' => $lastUser->name,
                    'position' => $lastPosition,
                ])
                ->log("Direct approval email sent to {$lastPosition} ({$lastUser->name}) for Pre-MPDR {$noReg}");

            DB::commit();

            ProcessApproval::dispatch($lastUser, $form, $lastDetail)->delay(now()->addSeconds(2));

            $data = [
                'title' => 'Urgent Pre-MPDR Approval Needed',
                'message' => 'Document ' . $form->no . ' was sent directly to you (' . $lastPosition . ') for approval.',
                'form_no' => $form->no,
            ];
            $lastUser->notify(new MpdrNotification($data, 'approval_request'));

            return $lastUser;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
