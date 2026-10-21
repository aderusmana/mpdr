<?php

namespace App\Services\Mpdr;

use App\Jobs\Mpdr\ProcessApproval;
use App\Jobs\Mpdr\sendResultToUser;
use App\Jobs\Mpdr\sendUpdateToAdmin;
use App\Models\MPDR\MpdrApprover;
use App\Models\MPDR\MpdrForm;
use App\Models\User;
use App\Notifications\MpdrNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MpdrApprovalService
{
    /**
     * Get users available to be approvers.
     *
     * @return Collection
     */
    public function getApproverListData(): Collection
    {
        try {
            $approver = User::with('position')->role(['approver', 'Approver'])->get();
            if ($approver->isEmpty()) {
                $approver = User::with('position')->get();
            }
        } catch (\Exception $e) {
            $approver = User::with('position')->get();
        }

        return $approver;
    }

    /**
     * Get list of master approvers configured for a user.
     *
     * @param string $userNik
     * @return Collection
     */
    public function getSelectedApproverList(string $userNik): Collection
    {
        $approverList = MpdrApprover::with('approverUser.position')
            ->where('user_nik', $userNik)
            ->get();

        if ($approverList->isEmpty()) {
            $firstUserNik = MpdrApprover::value('user_nik');
            if ($firstUserNik) {
                $approverList = MpdrApprover::with('approverUser.position')
                    ->where('user_nik', $firstUserNik)
                    ->get();
            }
        }

        $approverList->transform(function ($item) {
            $item->position = $item->approverUser?->position?->name ?? '-';
            return $item;
        });

        return $approverList;
    }

    /**
     * Save/update configured approver list.
     *
     * @param string $userNik
     * @param array $approvers
     * @return void
     */
    public function updateApproverOrder(string $userNik, array $approvers): void
    {
        DB::beginTransaction();

        try {
            MpdrApprover::where('user_nik', $userNik)->delete();

            if (!empty($approvers)) {
                foreach ($approvers as $approver) {
                    MpdrApprover::create([
                        'user_nik'        => $userNik,
                        'approver_nik'    => $approver['nik'],
                        'approver_name'   => $approver['name'],
                        'approver_status' => $approver['status'],
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Build the approval list rows for DataTables.
     *
     * @param User $user
     * @return array
     */
    public function getApprovalListData(User $user): array
    {
        $nik = $user->nik;

        $query = MpdrForm::with(['approvedDetail', 'initiatorDetail'])->where('status', 'In Approval');

        $isGlobalViewer = $user->hasRole(['super-admin', 'Super-Admin', 'admin', 'Admin', 'gm', 'GM'])
            || $user->can('view approval mpdr');

        if (!$isGlobalViewer) {
            $query->where(function ($q) use ($nik) {
                $q->whereHas('initiatorDetail', function ($q2) use ($nik) {
                    $q2->where('initiator_nik', $nik);
                })
                ->orWhereHas('approvedDetail', function ($q2) use ($nik) {
                    $q2->where('approver_nik', $nik);
                });
            });
        }

        $forms = $query->orderBy('created_at', 'desc')->get();
        $approvalRows = [];

        foreach ($forms as $form) {
            $approvedCount = $form->approvedDetail->filter(function ($d) {
                if (!$d->status) return false;
                $st = strtolower($d->status);
                return in_array($st, ['approve', 'approve with review', 'approved', 'approved with review'], true);
            })->count();

            $totalApprovers = $form->approvedDetail->count();

            $hasPendingGm = $form->approvedDetail->contains(function ($detail) {
                $approver = User::where('nik', $detail->approver_nik)->first();
                return $detail->status === 'pending' && $approver && $approver->hasRole('gm');
            });

            $canDirectToGm = $hasPendingGm
                && !$form->gm_direct_approval
                && !$form->isGmEligibleForApproval()
                && strtolower(trim($form->initiatorDetail?->status ?? '')) !== 'not approve'
                && !$form->approvedDetail->contains(function ($detail) {
                    return strtolower(trim($detail->status ?? '')) === 'not approve';
                });

            $directButtonAdded = false;
            $pendingDetails = $form->approvedDetail->where('status', 'pending');

            foreach ($pendingDetails as $detail) {
                $approverUser = User::where('nik', $detail->approver_nik)->first();
                if ($approverUser && $approverUser->hasRole('gm')) {
                    if (!$form->isGmEligibleForApproval()) {
                        continue;
                    }
                }

                if (!$isGlobalViewer && $detail->approver_nik !== $nik) {
                    continue;
                }

                $approvalRows[] = [
                    'id' => $form->id,
                    'no' => $form->no,
                    'product_name' => $form->product_name,
                    'created_at' => $form->created_at ? $form->created_at->toDateTimeString() : null,
                    'status' => $form->status,
                    'approved_count' => $approvedCount,
                    'total_approvers' => $totalApprovers,
                    'approver_nik' => $detail->approver_nik,
                    'approver_name' => $detail->approver_name,
                    'approver_status' => $detail->status,
                    'token' => $detail->token,
                    'can_direct_to_gm' => $canDirectToGm && !$directButtonAdded,
                ];
                $directButtonAdded = true;
            }
        }

        return $approvalRows;
    }

    /**
     * Check and update the form status (Approved, Rejected, or remaining In Approval).
     *
     * @param string $noReg
     * @return bool
     */
    public function checkFormInApprovalStatus(string $noReg): bool
    {
        $form = MpdrForm::with(['approvedDetail', 'initiatorDetail'])
            ->where('status', 'In Approval')
            ->where('no', $noReg)
            ->first();

        if (!$form) {
            return false;
        }

        $approvalStatuses = ['approve', 'approved', 'approve with review', 'approved with review'];
        $details = $form->approvedDetail->concat(collect([$form->initiatorDetail])->filter());

        foreach ($details as $detail) {
            $status = strtolower(trim($detail->status ?? ''));

            if ($status === 'not approve') {
                $form->status = 'Rejected';
                $form->route_to = null;
                $form->save();
                return false;
            }

            if ($status !== 'vacant' && !in_array($status, $approvalStatuses, true)) {
                return true;
            }
        }

        $form->status = 'Approved';
        $form->route_to = null;
        $form->save();

        return false;
    }

    /**
     * Send email and notifications to super admins on status update.
     *
     * @param string $noReg
     * @param User $approver
     * @return void
     */
    public function sendMailToAdmin(string $noReg, User $approver): void
    {
        $form = MpdrForm::with([
            'revision', 'detail', 'category', 'channel', 'description',
            'certification', 'competitor', 'packaging', 'market',
            'approvedDetail', 'initiatorDetail'
        ])->where('no', $noReg)->first();

        if (!$form) {
            return;
        }

        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'super-admin');
        })->get();

        foreach ($admins as $admin) {
            sendUpdateToAdmin::dispatch($admin, $form, $approver)->delay(10);

            $data = [
                'title' => 'MPDR ' . $form->no,
                'message' => 'Was ' . ($form->status === 'Rejected' ? 'rejected' : 'approved') . ' by ' . $approver->name . '.',
                'user_id' => $form->user_id,
            ];
            $admin->notify(new MpdrNotification($data, 'updates'));
        }
    }

    /**
     * Send email to pending approvers except the GM.
     *
     * @param string $noReg
     * @return void
     */
    public function sendMailToApproversExceptGM(string $noReg): void
    {
        $form = MpdrForm::with([
            'revision', 'detail', 'category', 'channel', 'description',
            'certification', 'competitor', 'packaging', 'market',
            'initiatorDetail', 'approvedDetail'
        ])->where('no', $noReg)->first();

        if ($form && $form->status === 'In Approval') {
            foreach ($form->approvedDetail as $detail) {
                $user = User::where('nik', $detail->approver_nik)->first();
                if ($user && !$user->hasRole('gm') && $detail->status === 'pending') {
                    ProcessApproval::dispatch($user, $form, $detail)->delay(10);

                    $data = [
                        'title' => 'New MPDR for Approval',
                        'message' => $form->no . ' needs your approval.',
                        'user_id' => $form->user_id,
                    ];
                    $user->notify(new MpdrNotification($data, 'approval_request'));
                }
            }
        }
    }

    /**
     * Send email and app notifications to users/initiators on completion.
     *
     * @param string $noReg
     * @return void
     */
    public function sendMailToUser(string $noReg): void
    {
        $form = MpdrForm::with([
            'revision', 'detail', 'category', 'channel', 'description',
            'certification', 'competitor', 'packaging', 'market',
            'initiatorDetail', 'approvedDetail'
        ])->where('no', $noReg)->first();

        if (!$form) {
            return;
        }

        if ($form->status === 'Approved') {
            $allApproverNik = $form->approvedDetail()->pluck('approver_nik');
            $allApprover = User::whereIn('nik', $allApproverNik)->get();
            $allAdmin = User::whereHas('roles', function ($query) {
                $query->where('name', 'super-admin');
            })->get();
            $allUser = $allApprover->merge($allAdmin)->unique('id');

            foreach ($allUser as $user) {
                sendResultToUser::dispatch($user, $form)->delay(10);

                $data = [
                    'title' => 'MPDR approved',
                    'message' => $form->no . ' has been approved.',
                    'user_id' => $form->user_id,
                ];
                $user->notify(new MpdrNotification($data, 'approved'));
            }

            activity()
                ->performedOn($form)
                ->inLog('mpdr')
                ->event('Final')
                ->withProperties(['no' => $form->no, 'action' => 'approved'])
                ->log($form->no . ' has been Approved at ' . now());

        } elseif ($form->status === 'Rejected') {
            $initiator = $form->initiatorDetail ? User::where('nik', $form->initiatorDetail->initiator_nik)->first() : null;
            $allAdmin = User::whereHas('roles', function ($query) {
                $query->where('name', 'super-admin');
            })->get();
            $allUser = $allAdmin;
            if ($initiator) {
                $allUser = $allUser->push($initiator);
            }
            $allUser = $allUser->unique('id');

            foreach ($allUser as $user) {
                sendResultToUser::dispatch($user, $form)->delay(10);

                $data = [
                    'title' => 'MPDR rejected',
                    'message' => $form->no . ' has been rejected.',
                    'user_id' => $form->user_id,
                ];
                $user->notify(new MpdrNotification($data, 'rejected'));
            }

            activity()
                ->performedOn($form)
                ->inLog('mpdr')
                ->event('Final')
                ->withProperties(['no' => $form->no, 'action' => 'rejected'])
                ->log($form->no . ' has been Rejected at ' . now());

        } elseif ($form->status === 'In Approval') {
            $this->sendMailToGM($noReg);
        }
    }

    /**
     * Send email and notification to GM if eligible.
     *
     * @param string $noReg
     * @return void
     */
    public function sendMailToGM(string $noReg): void
    {
        $form = MpdrForm::with([
            'revision', 'detail', 'category', 'channel', 'description',
            'certification', 'competitor', 'packaging', 'market',
            'approvedDetail', 'initiatorDetail'
        ])->where('no', $noReg)->first();

        if ($form && $form->status === 'In Approval' && !$form->gm_direct_approval) {
            if ($form->isGmEligibleForApproval()) {
                $gm = null;
                $gmDetail = null;

                foreach ($form->approvedDetail as $detail) {
                    $user = User::where('nik', $detail->approver_nik)->first();
                    if ($user && $user->hasRole(['gm', 'GM'])) {
                        $gm = $user;
                        $gmDetail = $detail;
                        if ($detail->status !== 'pending' && $detail->token === null) {
                            $gm = null;
                            break;
                        }
                    }
                }

                if ($gm && $gmDetail && $gmDetail->status === 'pending') {
                    ProcessApproval::dispatch($gm, $form, $gmDetail)->delay(10);

                    $data = [
                        'title' => 'New MPDR for Approval',
                        'message' => $form->no . ' needs your approval.',
                        'user_id' => $form->user_id,
                    ];
                    $gm->notify(new MpdrNotification($data, 'approval_request'));
                }
            }
        }
    }
}
