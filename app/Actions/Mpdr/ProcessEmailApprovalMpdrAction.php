<?php

namespace App\Actions\Mpdr;

use App\Models\MPDR\MpdrForm;
use App\Models\User;
use App\Services\Mpdr\MpdrApprovalService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessEmailApprovalMpdrAction
{
    public function __construct(
        protected MpdrApprovalService $approvalService
    ) {}

    /**
     * Process MPDR email approval (1-click or with review/rejection comment).
     *
     * @param string|null $formNo
     * @param string|null $approverNik
     * @param string|null $status
     * @param string|null $token
     * @param string|null $comment
     * @return array
     */
    public function execute(?string $formNo, ?string $approverNik, ?string $status, ?string $token, ?string $comment = null): array
    {
        $form_no = $formNo;
        $result = 'Failed';
        $initiatorApproved = false;
        $form = null;
        $approver = null;
        $approverDetail = null;

        try {
            if (!$token || !$approverNik) {
                throw new \Exception('This approval link is invalid or has expired.');
            }

            $approver = User::where('nik', $approverNik)->first();
            if (!$approver) {
                throw new \Exception('The approver could not be found.');
            }

            DB::beginTransaction();

            $form = MpdrForm::with([
                'revision', 'detail', 'category', 'channel', 'description',
                'certification', 'competitor', 'packaging', 'market',
                'approvedDetail', 'initiatorDetail', 'preMpdr', 'requester', 'user'
            ])->where('no', $formNo)->lockForUpdate()->first();

            if (!$form || $form->status !== 'In Approval') {
                DB::rollBack();
                $result = 'This form has already been processed or finalized.';
                return compact('form_no', 'result', 'status', 'form', 'approver', 'approverDetail', 'comment');
            }

            if ($approver->hasRole(['gm', 'GM']) && !$form->isGmEligibleForApproval()) {
                throw new \Exception('The GM cannot approve this form because an approval has been rejected or it has not been sent directly to the GM.');
            }

            $initiatorDetail = $form->initiatorDetail()->lockForUpdate()->first();
            if ($initiatorDetail && $initiatorDetail->initiator_nik === $approverNik) {
                if ($initiatorDetail->status !== 'pending' || !$initiatorDetail->token) {
                    DB::rollBack();
                    $result = 'You have already approved this form previously.';
                    $status = $initiatorDetail->status ?? $status;
                    $comment = $initiatorDetail->comment ?? $comment;
                    $approverDetail = $initiatorDetail;
                    return compact('form_no', 'result', 'status', 'form', 'approver', 'approverDetail', 'comment');
                }

                if (!hash_equals((string) $initiatorDetail->token, (string) $token)) {
                    throw new \Exception('This approval link is invalid or has expired.');
                }

                $initiatorDetail->status = $status;
                if ($comment !== null) {
                    $initiatorDetail->comment = $comment;
                }
                $initiatorDetail->approved_date = now();
                $initiatorDetail->token = null;
                $initiatorDetail->save();
                $initiatorApproved = in_array($status, ['approve', 'approve with review'], true);
            } else {
                $approverDetail = $form->approvedDetail()
                    ->where('approver_nik', $approverNik)
                    ->lockForUpdate()
                    ->first();

                if ($approverDetail && ($approverDetail->status !== 'pending' || !$approverDetail->token)) {
                    DB::rollBack();
                    $result = 'You have already approved this form previously.';
                    $status = $approverDetail->status ?? $status;
                    $comment = $approverDetail->comment ?? $comment;
                    return compact('form_no', 'result', 'status', 'form', 'approver', 'approverDetail', 'comment');
                }

                if (
                    !$approverDetail
                    || !$approverDetail->token
                    || !hash_equals((string) $approverDetail->token, (string) $token)
                ) {
                    throw new \Exception('This approval link is invalid or has expired.');
                }

                $approverDetail->status = $status;
                if ($comment !== null) {
                    $approverDetail->comment = $comment;
                }
                $approverDetail->token = null;
                $approverDetail->approved_date = now();
                $approverDetail->save();

                if ($approver->hasRole(['gm', 'GM'])) {
                    foreach ($form->approvedDetail as $detail) {
                        // Jangan ubah baris approval milik GM sendiri
                        if ($detail->id === $approverDetail->id || $detail->approver_nik === $approver->nik) {
                            continue;
                        }

                        $detailStatus = strtolower(trim($detail->status ?? ''));
                        if ($detailStatus === 'vacant' || $detailStatus === 'pending') {
                            $detail->approved_date = now();
                            $detail->token = null;
                            $detail->status = 'approve with review';
                            $detail->comment = 'Approved by GM';
                            $detail->save();
                        }
                    }

                    if ($form->initiatorDetail) {
                        $initStatus = strtolower(trim($form->initiatorDetail->status ?? ''));
                        if ($initStatus === 'vacant' || $initStatus === 'pending') {
                            $form->initiatorDetail->approved_date = now();
                            $form->initiatorDetail->token = null;
                            $form->initiatorDetail->status = 'approve with review';
                            $form->initiatorDetail->comment = 'Approved by GM';
                            $form->initiatorDetail->save();
                        }
                    }
                }
            }

            activity()
                ->performedOn($form)
                ->inLog('mpdr')
                ->event(ucfirst($status))
                ->causedBy($approver)
                ->withProperties([
                    'no' => $formNo,
                    'action' => $status === 'not approve' ? 'not approved' : ($status === 'approve with review' ? 'approved with review' : 'approved'),
                ])
                ->log(ucfirst($status) . ' Mpdr Form ' . $formNo . ' by ' . $approver->name . ' at ' . now());

            $this->approvalService->checkFormInApprovalStatus($formNo);

            DB::commit();
            $result = 'Success';

            if ($initiatorApproved) {
                $this->approvalService->sendMailToApproversExceptGM($formNo);
            }
            $this->approvalService->sendMailToAdmin($formNo, $approver);
            $this->approvalService->sendMailToUser($formNo);
        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            Log::warning('MPDR email approval failed', [
                'form_no' => $formNo,
                'approver_nik' => $approverNik,
                'error' => $e->getMessage(),
            ]);
        }

        return compact('form_no', 'result', 'status', 'form', 'approver', 'approverDetail', 'comment');
    }
}
