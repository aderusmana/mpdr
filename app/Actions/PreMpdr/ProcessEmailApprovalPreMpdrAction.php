<?php

namespace App\Actions\PreMpdr;

use App\Models\PREMPDR\PreMpdrForm;
use App\Models\User;
use App\Services\PreMpdr\PreMpdrApprovalService;
use Illuminate\Support\Facades\DB;

class ProcessEmailApprovalPreMpdrAction
{
    public function __construct(
        protected PreMpdrApprovalService $approvalService
    ) {}

    /**
     * Process Pre-MPDR email approval (1-click or with review comment).
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
        $form = null;
        $approver = null;
        $approverDetail = null;

        DB::beginTransaction();

        try {
            $form = PreMpdrForm::with([
                'revision', 'detail', 'category', 'channel', 'description',
                'certification', 'competitor', 'packaging', 'market',
                'approvedDetail', 'user'
            ])->where('no', $formNo)->lockForUpdate()->first();

            if (!$form) {
                throw new \Exception('Form not found');
            }

            $approverDetail = $this->approvalService->nextPendingApproval($form, true);
            $approver = User::where('nik', $approverNik)->first();

            if (
                $token && $approverDetail && $approverDetail->approver === $approverNik
                && $approverDetail->token && hash_equals((string) $approverDetail->token, (string) $token)
            ) {
                $approverDetail->status = $status;
                if ($comment !== null) {
                    $approverDetail->comment = $comment;
                }
                $approverDetail->token = null;
                $approverDetail->approved_date = now();
                $approverDetail->save();
                $result = 'Success';

                activity()
                    ->performedOn($form)
                    ->inLog('prempdr')
                    ->event(ucfirst($status))
                    ->causedBy($approver)
                    ->withProperties([
                        'no' => $formNo,
                        'action' => $status === 'not approve' ? 'not approved' : ($status === 'approve with review' ? 'approved with review' : 'approved'),
                    ])
                    ->log(ucfirst($status) . ' PreMpdr Form ' . $formNo . ' by ' . ($approver ? $approver->name : $approverNik) . ' at ' . now());

                $isStillInApproval = $this->approvalService->checkFormInApprovalStatus($formNo);

                DB::commit();

                if ($isStillInApproval) {
                    $this->approvalService->sendMailToApprover($formNo);
                }
                $this->approvalService->sendMailToUser($formNo);
            } else {
                DB::rollBack();
                $result = 'You have already approved this form previously.';
                $status = $approverDetail?->status ?? $status;
            }

            return compact('form_no', 'result', 'status', 'form', 'approver', 'approverDetail', 'comment');
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'form_no' => $formNo,
                'result' => 'Failed',
                'status' => $status,
                'form' => $form,
                'approver' => $approver,
                'approverDetail' => $approverDetail,
                'comment' => $comment,
            ];
        }
    }
}
