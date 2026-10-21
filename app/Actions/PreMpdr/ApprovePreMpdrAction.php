<?php

namespace App\Actions\PreMpdr;

use App\Models\PREMPDR\PreMpdrForm;
use App\Models\User;
use App\Services\PreMpdr\PreMpdrApprovalService;
use Illuminate\Support\Facades\DB;

class ApprovePreMpdrAction
{
    public function __construct(
        protected PreMpdrApprovalService $approvalService
    ) {}

    /**
     * Process in-app Pre-MPDR form approval.
     *
     * @param string $noReg Form registration number
     * @param array $data Validated input data ('action', 'comment', 'target_approver_nik')
     * @param User $user Current authenticated user
     * @return array
     * @throws \Exception
     */
    public function execute(string $noReg, array $data, User $user): array
    {
        DB::beginTransaction();

        try {
            $nik = $user->nik;
            $form = PreMpdrForm::where('no', $noReg)->lockForUpdate()->first();

            if (!$form) {
                throw new \Exception('Form not found.');
            }

            $detailToUpdate = $this->approvalService->nextPendingApproval($form, true);
            $isAdmin = $user->hasRole(['super-admin', 'Super-Admin']) || $user->can('super-admin');

            if (!$detailToUpdate || (!$isAdmin && $detailToUpdate->approver !== $nik)) {
                throw new \Exception('You have already processed this form or it is not your turn.');
            }

            if ($isAdmin && !empty($data['target_approver_nik']) && $detailToUpdate->approver !== $data['target_approver_nik']) {
                throw new \Exception('Only the current approver can be taken over.');
            }

            $action = $data['action'];
            $detailToUpdate->status = $action;
            $detailToUpdate->approved_date = now();
            if ($action !== 'approve') {
                $detailToUpdate->comment = $data['comment'] ?? null;
            }
            $detailToUpdate->token = null;
            $detailToUpdate->save();

            $isTakeover = ($user->nik !== $detailToUpdate->approver);
            $logNote = ucfirst($action) . ' PreMpdr Form ' . $noReg . ' by ' . $user->name;
            if ($isTakeover) {
                $logNote .= ' (Takeover on behalf of ' . ($detailToUpdate->name ?? $detailToUpdate->approver) . ')';
            }

            activity()
                ->performedOn($form)
                ->inLog('prempdr')
                ->event(ucfirst($action))
                ->causedBy($user)
                ->withProperties([
                    'no' => $noReg,
                    'action' => $action,
                    'takeover' => $isTakeover,
                    'on_behalf_of' => $detailToUpdate->name ?? $detailToUpdate->approver,
                ])
                ->log($logNote);

            $isStillInApproval = $this->approvalService->checkFormInApprovalStatus($noReg);

            DB::commit();

            if ($isStillInApproval) {
                $this->approvalService->sendMailToApprover($noReg);
            }
            $this->approvalService->sendMailToUser($noReg);

            return [
                'success' => true,
                'message' => 'Form successfully processed!' . ($isTakeover ? ' (Takeover by Super-Admin)' : ''),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
