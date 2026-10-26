<?php

namespace App\Services\Mpdr;

use App\Models\MPDR\MpdrForm;
use App\Models\User;
use App\Traits\ConditionQueryTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity as SpatieActivity;

class MpdrService
{
    use ConditionQueryTrait;

    /**
     * Generate the next unique MPDR registration number.
     *
     * @return string
     */
    public function generateNoReg(): string
    {
        $year = date('y');
        $prefix = "{$year}MPDR";
        $lastPcr = MpdrForm::where('no', 'like', "{$prefix}%")->orderBy('no', 'desc')->first();

        if ($lastPcr) {
            $lastNo = (int) substr($lastPcr->no_reg ?? $lastPcr->no, -4);
            $newNo = $lastNo + 1;
        } else {
            $newNo = 1;
        }

        do {
            $newNoReg = $prefix . str_pad($newNo, 4, '0', STR_PAD_LEFT);
            $existingPcr = MpdrForm::where('no', $newNoReg)->first();
            if ($existingPcr) {
                $newNo++;
            }
        } while ($existingPcr);

        return $newNoReg;
    }

    /**
     * Retrieve and process the MPDR form list for DataTables.
     *
     * @param Request $request
     * @return \Illuminate\Support\Collection
     */
    public function getFormList(Request $request)
    {
        $user = Auth::user();
        $query = MpdrForm::with(['approvedDetail.user.position', 'approvedDetail.user.roles', 'initiatorDetail', 'preMpdr']);

        if ($user && !$user->hasRole(['super-admin', 'Super-Admin'])) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('requester_id', $user->id)
                  ->orWhereHas('preMpdr', function ($sub) use ($user) {
                      $sub->where('user_id', $user->id);
                  });
            });
        }

        if ($request->has('searchBuilder')) {
            $this->applySearchBuilder($query, $request->input('searchBuilder'));
        }

        $forms = $query->latest()->get();

        $forms->map(function (MpdrForm $form) {
            $form->route_to = null;
            $form->approved_count = 0;
            $form->total_approvers = 0;
            $form->is_route_to_gm = false;
            $form->can_direct_to_gm = false;

            if ($form->status === 'In Approval') {
                $pendingApprovers = $form->approvedDetail->where('status', 'pending');

                // Filter pending approvers: hide GM until GM is eligible
                $filteredPending = $pendingApprovers->filter(function ($pDetail) use ($form) {
                    $approverUser = $pDetail->user;
                    if ($approverUser && $approverUser->hasRole(['gm', 'GM'])) {
                        return $form->isGmEligibleForApproval();
                    }
                    return true;
                });

                if ($filteredPending->isNotEmpty()) {
                    $form->route_to = $filteredPending->map(function ($detail) {
                        return $detail->user?->position?->name ?: $detail->approver_name;
                    })->unique()->implode(', ');

                    $hasGm = false;
                    foreach ($filteredPending as $pDetail) {
                        $approverUser = $pDetail->user;
                        if ($approverUser && $approverUser->hasRole(['gm', 'GM'])) {
                            $hasGm = true;
                            break;
                        }
                    }
                    $form->is_route_to_gm = $hasGm;
                } else {
                    $form->route_to = 'Finalizing';
                }

                $form->approved_count = $form->approvedDetail->filter(function ($detail) {
                    $status = strtolower(trim($detail->status ?? ''));
                    return in_array($status, [
                        'approve',
                        'approved',
                        'approve with review',
                        'approved with review',
                    ], true);
                })->count();

                $form->total_approvers = $form->approvedDetail->reject(function ($detail) {
                    return strtolower(trim($detail->status ?? '')) === 'vacant';
                })->count();

                $hasPendingGm = $pendingApprovers->contains(function ($detail) {
                    $approverUser = $detail->user;
                    return $approverUser && $approverUser->hasRole('gm');
                });

                $hasRejectedApproval = $form->approvedDetail->contains(function ($detail) {
                    return strtolower(trim($detail->status ?? '')) === 'not approve';
                });

                $form->can_direct_to_gm = $hasPendingGm
                    && !$form->gm_direct_approval
                    && !$form->isGmEligibleForApproval()
                    && strtolower(trim($form->initiatorDetail?->status ?? '')) !== 'not approve'
                    && !$hasRejectedApproval;
            }

            return $form;
        });

        return $forms;
    }

    /**
     * Retrieve report data formatted for MPDR.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getReportData()
    {
        $user = Auth::user();
        $query = MpdrForm::with([
            'revision', 'detail', 'category', 'channel', 'description',
            'certification', 'competitor', 'packaging', 'market',
            'approvedDetail', 'user.position', 'requester.position'
        ])
        ->whereIn('status', ['Approved', 'Rejected']);

        if ($user && !$user->hasRole(['super-admin', 'Super-Admin'])) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('requester_id', $user->id)
                  ->orWhereHas('preMpdr', function ($sub) use ($user) {
                      $sub->where('user_id', $user->id);
                  });
            });
        }

        $forms = $query->orderBy('created_at', 'desc')->get();

        return $forms->map(function (MpdrForm $item) {
            $item->new_created_at = $item->created_at->format('j F Y');
            $item->submitted_by = $item->user?->name ?? $item->requester?->name ?? $item->initiator ?? '-';
            return $item;
        });
    }

    /**
     * Retrieve full details for MPDR modal view.
     *
     * @param string $formNo
     * @return array
     */
    public function getDetailData(string $formNo): array
    {
        $user = Auth::user();
        $query = MpdrForm::with([
            'revision', 'detail', 'category', 'channel', 'description',
            'certification', 'competitor', 'packaging', 'market',
            'approvedDetail', 'initiatorDetail', 'preMpdr', 'user.position', 'requester.position'
        ])->where('no', $formNo);

        if ($user && !$user->hasRole(['super-admin', 'Super-Admin'])) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('requester_id', $user->id)
                  ->orWhereHas('preMpdr', function ($sub) use ($user) {
                      $sub->where('user_id', $user->id);
                  });
            });
        }

        $formData = $query->firstOrFail();

        if ($formData->preMpdr) {
            $formData->brand_name = $formData->preMpdr->brand_name;
        }

        $ordered = $formData->approvedDetail()->with(['user.position', 'user.roles'])->orderBy('created_at', 'asc')->get();

        $formData->approved_detail_ordered = $ordered->filter(function ($d) use ($formData) {
            $approverUser = $d->user;
            if ($approverUser && $approverUser->hasRole(['gm', 'GM'])) {
                if (strtolower($d->status ?? '') === 'pending' && !$formData->isGmEligibleForApproval()) {
                    return false;
                }
            }
            return true;
        })->map(function ($d) {
            $positionName = $d->user?->position?->name;

            return [
                'approver_name' => $d->approver_name,
                'approver_nik' => $d->approver_nik,
                'approver_position' => $positionName,
                'status' => $d->status,
                'approved_date' => $d->approved_date ? (is_string($d->approved_date) ? $d->approved_date : $d->approved_date->toDateTimeString()) : null,
                'comment' => $d->comment,
            ];
        })->values()->toArray();

        try {
            $history = SpatieActivity::with('causer')->where('log_name', 'mpdr')
                ->where('subject_type', MpdrForm::class)
                ->where('subject_id', $formData->id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($h) {
                    return [
                        'id' => $h->id,
                        'description' => $h->description,
                        'properties' => $h->properties ?? null,
                        'causer' => $h->causer ? ($h->causer->name ?? $h->causer->id) : null,
                        'created_at' => $h->created_at->toDateTimeString(),
                    ];
                })->toArray();
        } catch (\Throwable $e) {
            $history = [];
        }

        $formData->history = $history;

        return $formData->toArray();
    }

    /**
     * Get approved Pre-MPDR forms that don't have an MPDR form yet.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPrempdrList()
    {
        return \App\Models\PREMPDR\PreMpdrForm::whereDoesntHave('Mpdr')
            ->where('status', 'Approved')
            ->pluck('no');
    }

    /**
     * Get Pre-MPDR data for auto-populating MPDR form.
     *
     * @param string|null $noReg
     * @return \App\Models\PREMPDR\PreMpdrForm|null
     */
    public function getPrempdrToMpdr(?string $noReg)
    {
        if (empty($noReg)) {
            return null;
        }

        return \App\Models\PREMPDR\PreMpdrForm::with([
            'user', 'detail', 'category', 'channel', 'description',
            'certification', 'competitor', 'packaging', 'market'
        ])->where('no', $noReg)->first();
    }
}
