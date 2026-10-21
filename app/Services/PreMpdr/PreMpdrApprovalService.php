<?php

namespace App\Services\PreMpdr;

use App\Jobs\PreMpdr\ProcessApproval;
use App\Jobs\PreMpdr\sendResultToUser;
use App\Models\PREMPDR\PreMpdrApprovedDetail;
use App\Models\PREMPDR\PreMpdrApprover;
use App\Models\PREMPDR\PreMpdrForm;
use App\Models\User;
use App\Notifications\MpdrNotification;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PreMpdrApprovalService
{
    /**
     * Get available levels for Pre-MPDR approver configuration.
     *
     * @return array
     */
    public function getApproverAvailableLevels(): array
    {
        $allLevels = range(2, 10);
        $usedLevels = PreMpdrApprover::pluck('level')->toArray();
        $availableLevels = array_diff($allLevels, $usedLevels);

        return array_values($availableLevels);
    }

    /**
     * Get users available to be set as approvers.
     *
     * @return Collection
     */
    public function getApproverListData(): Collection
    {
        try {
            $approvers = User::with('position')->role(['approver', 'Approver'])->get();
            if ($approvers->isEmpty()) {
                $approvers = User::with('position')->get();
            }
        } catch (\Exception $e) {
            $approvers = User::with('position')->get();
        }

        return $approvers;
    }

    /**
     * Get list of master approvers configured for a user.
     *
     * @param string $userNik
     * @return Collection
     */
    public function getSelectedApproverList(string $userNik): Collection
    {
        $approverList = PreMpdrApprover::with('approverUser.position')
            ->where('user_nik', $userNik)
            ->orderBy('level', 'asc')
            ->get();

        if ($approverList->isEmpty()) {
            $firstUserNik = PreMpdrApprover::value('user_nik');
            if ($firstUserNik) {
                $approverList = PreMpdrApprover::with('approverUser.position')
                    ->where('user_nik', $firstUserNik)
                    ->orderBy('level', 'asc')
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
     * Update master approver configuration.
     *
     * @param string $userNik
     * @param array $approverNiks
     * @param array $approverNames
     * @param array $approverStatuses
     * @param array $approverLevels
     * @return void
     */
    public function updateApproverOrder(string $userNik, array $approverNiks, array $approverNames, array $approverStatuses, array $approverLevels): void
    {
        DB::beginTransaction();
        try {
            PreMpdrApprover::where('user_nik', $userNik)->delete();

            if (!empty($approverNiks)) {
                foreach ($approverNiks as $index => $nik) {
                    PreMpdrApprover::create([
                        'user_nik'        => $userNik,
                        'approver_nik'    => $nik,
                        'approver_name'   => $approverNames[$index] ?? '',
                        'approver_status' => $approverStatuses[$index] ?? 'Active',
                        'level'           => $approverLevels[$index] ?? ($index + 1),
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
     * Delete an approver by NIK from master approvers.
     *
     * @param string $nik
     * @return bool
     */
    public function deleteApprover(string $nik): bool
    {
        return (bool) PreMpdrApprover::where('approver_nik', $nik)->delete();
    }

    /**
     * Get list of forms pending approval for the current user.
     *
     * @param User $user
     * @return Collection
     */
    public function getApprovalListData(User $user): Collection
    {
        $nik = $user->nik;
        $query = PreMpdrForm::with('approvedDetail')->where('status', 'In Approval');

        $isGlobalViewer = $user->hasRole(['super-admin', 'Super-Admin', 'admin', 'Admin', 'gm', 'GM'])
            || $user->can('view approval prempdr');

        if (!$isGlobalViewer) {
            $query->whereHas('approvedDetail', function ($q) use ($nik) {
                $q->where('approver', $nik);
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get next pending approver detail for a form.
     *
     * @param PreMpdrForm $form
     * @param bool $lock
     * @return PreMpdrApprovedDetail|null
     */
    public function nextPendingApproval(PreMpdrForm $form, bool $lock = false): ?PreMpdrApprovedDetail
    {
        $query = $form->approvedDetail()
            ->whereNull('status')
            ->orderBy('level')
            ->orderBy('id');

        if ($lock) {
            $query->lockForUpdate();
        }

        return $query->first();
    }

    /**
     * Format approver position label.
     *
     * @param PreMpdrApprovedDetail $detail
     * @return string
     */
    public function approverPositionLabel(PreMpdrApprovedDetail $detail): string
    {
        if ($detail->approver_position) {
            return $detail->approver_position;
        }

        $position = User::with('position')->where('nik', $detail->approver)->first()?->position?->name;

        return $position ?: $detail->name;
    }

    /**
     * Verify and update form status based on approval details.
     *
     * @param string $noReg
     * @return bool Returns true if form is still 'In Approval', false if finished (Approved or Rejected)
     */
    public function checkFormInApprovalStatus(string $noReg): bool
    {
        $form = PreMpdrForm::where('status', 'In Approval')
            ->where('no', $noReg)
            ->first();

        if ($form) {
            $details = $form->approvedDetail()
                ->orderBy('level')
                ->orderBy('id')
                ->get();

            if ($details->contains('status', 'not approve')) {
                $form->status = 'Rejected';
                $form->route_to = null;
                $form->save();
                return false;
            }

            $nextApprover = $details->firstWhere('status', null);
            if ($nextApprover) {
                $form->route_to = $this->approverPositionLabel($nextApprover);
                $form->save();
            } else {
                $form->status = 'Approved';
                $form->route_to = null;
                $form->save();
            }

            return $form->status === 'In Approval';
        }

        return false;
    }

    /**
     * Send email and notification to next pending approver.
     *
     * @param string $noReg
     * @return void
     */
    public function sendMailToApprover(string $noReg): void
    {
        $form = PreMpdrForm::with([
            'revision', 'detail', 'category', 'channel', 'description',
            'certification', 'competitor', 'packaging', 'market',
            'approvedDetail', 'user'
        ])->where('no', $noReg)->first();

        if ($form && $form->status === 'In Approval') {
            $approvedDetail = $this->nextPendingApproval($form);
            if (!$approvedDetail) {
                return;
            }

            if (!$approvedDetail->token) {
                $approvedDetail->token = Str::uuid();
                $approvedDetail->save();
            }

            $form->route_to = $this->approverPositionLabel($approvedDetail);
            $form->save();
            $approver = User::where('nik', $approvedDetail->approver)->first();

            if ($approver) {
                ProcessApproval::dispatch($approver, $form, $approvedDetail)->delay(10);

                $data = [
                    'title' => 'New Pre-MPDR for Approval',
                    'message' => $form->no . ' needs your approval.',
                    'user_id' => $form->user_id,
                ];
                $approver->notify(new MpdrNotification($data, 'approval_request'));
            }
        }
    }

    /**
     * Send email and app notification to users/admins on status updates, approvals, or rejections.
     *
     * @param string $noReg
     * @return void
     */
    public function sendMailToUser(string $noReg): void
    {
        $form = PreMpdrForm::with([
            'revision', 'detail', 'category', 'channel', 'description',
            'certification', 'competitor', 'packaging', 'market',
            'approvedDetail', 'user'
        ])->where('no', $noReg)->first();

        if (!$form) {
            return;
        }

        if ($form->status === 'Approved') {
            $allApproverNik = $form->approvedDetail()->pluck('approver');
            $allApprover = User::whereIn('nik', $allApproverNik)->get();
            $allAdmin = User::whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->get();
            $requester = $form->user ? collect([$form->user]) : collect();
            $allUser = $allApprover->merge($allAdmin)->merge($requester)->unique('id');

            foreach ($allUser as $user) {
                sendResultToUser::dispatch($user, $form)->delay(10);

                $data = [
                    'title' => 'Pre-MPDR approved',
                    'message' => $form->no . ' has been approved.',
                    'user_id' => $form->user_id,
                ];
                $user->notify(new MpdrNotification($data, 'approved'));
            }

            activity()
                ->performedOn($form)
                ->inLog('prempdr')
                ->event('Final')
                ->withProperties(['no' => $form->no, 'action' => 'approved'])
                ->log($form->no . ' has been Approved at ' . now());

        } elseif ($form->status === 'Rejected') {
            $initiatorDetail = $form->approvedDetail->where('level', 1)->first();
            $initiator = $initiatorDetail ? User::where('nik', $initiatorDetail->approver)->first() : null;
            $requester = $form->user;

            $allAdmin = User::whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->get();

            $allUser = $allAdmin;
            if ($initiator) {
                $allUser = $allUser->push($initiator);
            }
            if ($requester) {
                $allUser = $allUser->push($requester);
            }
            $allUser = $allUser->unique('id');

            foreach ($allUser as $user) {
                sendResultToUser::dispatch($user, $form)->delay(10);

                $data = [
                    'title' => 'Pre-MPDR rejected',
                    'message' => $form->no . ' has been rejected.',
                    'user_id' => $form->user_id,
                ];
                $user->notify(new MpdrNotification($data, 'rejected'));
            }

            activity()
                ->performedOn($form)
                ->inLog('prempdr')
                ->event('Final')
                ->withProperties(['no' => $form->no, 'action' => 'rejected'])
                ->log($form->no . ' has been Rejected at ' . now());

        } elseif ($form->status === 'In Approval') {
            $admins = User::whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->get();
            $requester = $form->user ? collect([$form->user]) : collect();
            $allNotify = $admins->merge($requester)->unique('id');

            $lastApproverName = optional($form->approvedDetail->whereIn('status', ['approve', 'approve with review'])->last())->name ?? 'Approver';

            foreach ($allNotify as $notifiedUser) {
                sendResultToUser::dispatch($notifiedUser, $form)->delay(10);

                $data = [
                    'title' => 'Pre-MPDR ' . $form->no,
                    'message' => 'No Pre-MPDR ' . $form->no . ' Has been approved by ' . $lastApproverName . '.',
                    'user_id' => $form->user_id,
                ];
                $notifiedUser->notify(new MpdrNotification($data, 'updates'));
            }
        }
    }
}
