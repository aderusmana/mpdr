<?php

namespace App\Actions\Mpdr;

use App\Models\MPDR\MpdrForm;
use App\Models\User;
use App\Notifications\MpdrNotification;
use App\Services\Mpdr\MpdrApprovalService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ApproveMpdrAction
{
    public function __construct(
        protected MpdrApprovalService $approvalService
    ) {}

    /**
     * Process in-app MPDR approval.
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
            $form = MpdrForm::with(['initiatorDetail', 'approvedDetail', 'user'])
                ->where('no', $noReg)
                ->firstOrFail();

            if ($form->status !== 'In Approval') {
                throw new \Exception('Form ini sudah tidak dalam status persetujuan.');
            }

            $isGm = $user->hasRole(['gm', 'GM']);
            if ($isGm && !$form->isGmEligibleForApproval()) {
                throw new \Exception('The GM cannot process this form because an approval has been rejected or it has not been sent directly to the GM.');
            }

            $action = $data['action'];
            $comment = $data['comment'] ?? null;
            $processed = false;

            // Initiator Detail check
            if ($form->initiatorDetail && $form->initiatorDetail->initiator_nik === $nik && $form->initiatorDetail->status === 'pending') {
                $form->initiatorDetail->update([
                    'status' => $action,
                    'comment' => ($action !== 'approve') ? $comment : null,
                    'approved_date' => now(),
                    'token' => null,
                ]);
                $processed = true;
            } else {
                // Approver Detail check
                $detailToUpdate = $form->approvedDetail()->where('approver_nik', $nik)->where('status', 'pending')->first();

                // Super-Admin Takeover Fallback
                if (!$detailToUpdate && ($user->hasRole(['super-admin', 'Super-Admin']) || $user->can('super-admin'))) {
                    if (!empty($data['target_approver_nik'])) {
                        $detailToUpdate = $form->approvedDetail()->where('approver_nik', $data['target_approver_nik'])->where('status', 'pending')->first();
                    }
                    if (!$detailToUpdate && $form->initiatorDetail && $form->initiatorDetail->status === 'pending') {
                        $form->initiatorDetail->update([
                            'status' => $action,
                            'comment' => ($action !== 'approve') ? $comment : null,
                            'approved_date' => now(),
                            'token' => null,
                        ]);
                        $processed = true;
                    }
                    if (!$detailToUpdate && !$processed) {
                        $detailToUpdate = $form->approvedDetail()->where('status', 'pending')->orderBy('id', 'asc')->first();
                    }
                }

                if ($detailToUpdate) {
                    $detailToUpdate->update([
                        'status' => $action,
                        'comment' => ($action !== 'approve' || !empty($comment)) ? $comment : null,
                        'approved_date' => now(),
                        'token' => null,
                    ]);
                    $processed = true;

                    if ($isGm) {
                        foreach ($form->approvedDetail as $detail) {
                            // Jangan ubah detail milik GM sendiri
                            if ($detail->id === $detailToUpdate->id || $detail->approver_nik === $nik) {
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
            }

            if (!$processed) {
                throw new \Exception('It is not your turn to approve this form, or you have already processed it.');
            }

            // Update main form status
            $this->approvalService->checkFormInApprovalStatus($noReg);
            $form->refresh();

            $requester = $form->user;

            // Notification workflow
            if ($action === 'not approve') {
                if ($requester) {
                    $rejectData = [
                        'title' => 'MPDR Rejected',
                        'message' => 'Your document (' . $form->no . ') was rejected by ' . $user->name . '.',
                        'form_no' => $form->no,
                    ];
                    $requester->notify(new MpdrNotification($rejectData, 'rejected'));
                }
            } else {
                $allUsers = User::role('user')->get()->except($user->id);
                if ($allUsers->isNotEmpty()) {
                    $broadcastData = [
                        'title' => 'MPDR Status Update',
                        'message' => 'Document ' . $form->no . ' was approved by ' . $user->name . '.',
                        'form_no' => $form->no,
                    ];
                    Notification::send($allUsers, new MpdrNotification($broadcastData, 'updates'));
                }

                if ($form->status === 'In Approval') {
                    $nextApproverNik = null;
                    if ($form->initiatorDetail && $form->initiatorDetail->status === 'pending') {
                        $nextApproverNik = $form->initiatorDetail->initiator_nik;
                    } else {
                        $nextDetail = $form->approvedDetail()->where('status', 'pending')->orderBy('created_at', 'asc')->first();
                        if ($nextDetail) {
                            $nextApproverNik = $nextDetail->approver_nik;
                        }
                    }

                    if ($nextApproverNik) {
                        $nextApproverUser = User::where('nik', $nextApproverNik)->first();
                        if ($nextApproverUser) {
                            $taskData = [
                                'title' => 'MPDR Approval Request',
                                'message' => 'MPDR request ' . $form->no . ' requires your approval.',
                                'form_no' => $form->no,
                            ];
                            $nextApproverUser->notify(new MpdrNotification($taskData, 'approval_request'));
                        }
                    }
                } elseif ($form->status === 'Approved') {
                    if ($requester) {
                        $finalData = [
                            'title' => 'MPDR Fully Approved',
                            'message' => 'Your document (' . $form->no . ') has been fully approved.',
                            'form_no' => $form->no,
                        ];
                        $requester->notify(new MpdrNotification($finalData, 'approved'));
                    }
                }
            }

            activity()
                ->performedOn($form)
                ->inLog('mpdr')
                ->event(ucfirst($action))
                ->causedBy($user)
                ->log(ucfirst($action) . ' Mpdr Form ' . $noReg);

            DB::commit();

            if ($user->hasRole('gm') && $action !== 'not approve' && $form->status !== 'Rejected') {
                try {
                    $this->approvalService->sendMailToAdmin($noReg, $user);
                    $this->approvalService->sendMailToUser($noReg);
                } catch (\Exception $e) {
                    // Do not break execution if email dispatch fails
                }
            }

            if ($form->status === 'In Approval') {
                $this->approvalService->sendMailToGM($noReg);
            }

            return [
                'success' => true,
                'message' => 'The form was processed successfully.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
