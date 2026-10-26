<?php

namespace App\Services\PreMpdr;

use App\Jobs\PreMpdr\ProcessApproval;
use App\Models\PREMPDR\PreMpdrApprovedDetail;
use App\Models\PREMPDR\PreMpdrForm;
use App\Models\User;
use App\Notifications\MpdrNotification;
use App\Traits\ConditionQueryTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PreMpdrService
{
    use ConditionQueryTrait;

    /**
     * Generate the next unique Pre-MPDR registration number.
     *
     * @return string
     */
    public function generateNoReg(): string
    {
        $year = date('y');
        $prefix = "{$year}PREMPDR";
        $lastPcr = PreMpdrForm::where('no', 'like', "{$prefix}%")->orderBy('no', 'desc')->first();

        if ($lastPcr) {
            $lastNo = (int) substr($lastPcr->no_reg ?? $lastPcr->no, -4);
            $newNo = $lastNo + 1;
        } else {
            $newNo = 1;
        }

        do {
            $newNoReg = $prefix . str_pad($newNo, 4, '0', STR_PAD_LEFT);
            $existingPcr = PreMpdrForm::where('no', $newNoReg)->first();
            if ($existingPcr) {
                $newNo++;
            }
        } while ($existingPcr);

        return $newNoReg;
    }

    /**
     * Determine the position label of an approver.
     *
     * @param PreMpdrApprovedDetail $detail
     * @return string
     */
    public function approverPositionLabel(PreMpdrApprovedDetail $detail): string
    {
        if ($detail->approver_position) {
            return $detail->approver_position;
        }

        if ($detail->relationLoaded('user') && $detail->user) {
            return $detail->user->position?->name ?: $detail->name;
        }

        $position = User::with('position')->where('nik', $detail->approver)->first()?->position?->name;

        return $position ?: $detail->name;
    }

    /**
     * Send email and app notification to the next pending approver.
     *
     * @param string $no_reg
     * @return void
     */
    public function sendMailToApprover(string $no_reg): void
    {
        $form = PreMpdrForm::with([
            'revision', 'detail', 'category', 'channel', 'description',
            'certification', 'competitor', 'packaging', 'market',
            'approvedDetail', 'user'
        ])->where('no', $no_reg)->first();

        if ($form && $form->status === 'In Approval') {
            $approvedDetail = $form->approvedDetail()
                ->whereNull('status')
                ->orderBy('level')
                ->orderBy('id')
                ->first();

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
     * Retrieve and process the form list for DataTables.
     *
     * @param Request $request
     * @return \Illuminate\Support\Collection
     */
    public function getFormList(Request $request)
    {
        $user = Auth::user();
        $query = PreMpdrForm::with(['approvedDetail.user.position']);

        if ($user && !$user->hasRole(['super-admin', 'Super-Admin'])) {
            $query->where('user_id', $user->id);
        }

        if ($request->has('searchBuilder')) {
            $this->applySearchBuilder($query, $request->input('searchBuilder'));
        }

        $forms = $query->orderBy('created_at', 'desc')->get();

        if ($forms->isNotEmpty()) {
            $forms->each(function (PreMpdrForm $form) {
                $details = $form->approvedDetail->sortBy([['level', 'asc'], ['id', 'asc']]);

                $total = $details->reject(function ($d) {
                    $st = strtolower(trim($d->status ?? ''));
                    $isVacantUser = $d->user && strtolower(trim($d->user->status ?? '')) === 'vacant';
                    return $st === 'vacant' || $isVacantUser;
                })->count();

                $approvedCount = $details->filter(function ($d) {
                    $st = strtolower(trim($d->status ?? ''));
                    return in_array($st, ['approve', 'approved', 'approve with review', 'approved with review'], true);
                })->count();

                // Preserve array format for DataTables progress badge: (${row.approved_detail[0].approved_count}/${row.approved_detail[0].total})
                $form->approved_count = $approvedCount;
                $form->total_approvers = $total;
                $form->setRelation('approvedDetail', collect([
                    (object) [
                        'form_id' => $form->id,
                        'approved_count' => $approvedCount,
                        'total' => $total,
                    ]
                ]));

                if ($form->status !== 'In Approval') {
                    $form->can_direct_to_last_approver = false;
                    return;
                }

                $hasRejected = $details->contains(function ($d) {
                    return strtolower(trim($d->status ?? '')) === 'not approve';
                });

                // Find next active pending approver (skipping vacant)
                $nextActiveApprover = null;
                foreach ($details as $detail) {
                    $st = strtolower(trim($detail->status ?? ''));
                    if (in_array($st, ['approve', 'approved', 'approve with review', 'approved with review', 'not approve', 'not approved', 'rejected'], true)) {
                        continue;
                    }
                    $isVacant = ($st === 'vacant') || ($detail->user && strtolower(trim($detail->user->status ?? '')) === 'vacant');
                    if ($isVacant) {
                        continue;
                    }
                    $nextActiveApprover = $detail;
                    break;
                }

                if ($nextActiveApprover) {
                    $form->route_to = $nextActiveApprover->approver_position
                        ?: $nextActiveApprover->user?->position?->name
                        ?: $nextActiveApprover->name
                        ?: $form->route_to;
                }

                $lastDetail = $details->sortByDesc('level')->first();
                $isLastPending = $lastDetail && in_array(strtolower(trim($lastDetail->status ?? '')), ['', 'pending'], true);

                $canDirect = !$hasRejected
                    && !$form->last_approver_direct_approval
                    && $isLastPending
                    && $lastDetail
                    && ($nextActiveApprover && $nextActiveApprover->id !== $lastDetail->id);

                $form->can_direct_to_last_approver = (bool) $canDirect;
                $form->last_approver_label = $lastDetail ? ($lastDetail->approver_position ?: 'Head S&M') : 'Head S&M';
            });
        }

        return $forms;
    }

    /**
     * Retrieve report data formatted for Pre-MPDR.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getReportData()
    {
        $user = Auth::user();
        $query = PreMpdrForm::with([
            'revision', 'detail', 'category', 'channel', 'description',
            'certification', 'competitor', 'packaging', 'market',
            'approvedDetail', 'user.position'
        ])
        ->whereIn('status', ['Approved', 'Rejected']);

        if ($user && !$user->hasRole(['super-admin', 'Super-Admin'])) {
            $query->where('user_id', $user->id);
        }

        $forms = $query->orderBy('created_at', 'desc')->get();

        return $forms->map(function (PreMpdrForm $item) {
            $item->new_created_at = $item->created_at->format('j F Y');
            $user = $item->user;
            $pos = $user?->position?->name ? ' (' . $user->position->name . ')' : '';
            $item->submitted_by = $user ? ($user->name . $pos) : '-';
            return $item;
        });
    }

    /**
     * Retrieve full details including approval tracker and history for Pre-MPDR modal view.
     *
     * @param string $formNo
     * @return array
     */
    public function getDetailData(string $formNo): array
    {
        $user = Auth::user();
        $query = PreMpdrForm::with([
            'revision', 'detail', 'category', 'channel', 'description',
            'certification', 'competitor', 'packaging', 'market',
            'user.position'
        ])->where('no', $formNo);

        if ($user && !$user->hasRole(['super-admin', 'Super-Admin'])) {
            $query->where('user_id', $user->id);
        }

        $formData = $query->firstOrFail();

        $approvedDetails = $formData->approvedDetail()->with('user.position')->orderBy('level', 'asc')->get();

        $totalApprovers = $approvedDetails->count();
        $processedCount = $approvedDetails->filter(function ($d) {
            return $d->status !== null && $d->status !== 'pending' && $d->status !== 'Vacant';
        })->count();

        $userPos = $formData->user?->position?->name ? ' (' . $formData->user->position->name . ')' : '';
        $steps = [];
        $steps[] = [
            'key' => 'submitted',
            'label' => 'Submitted by ' . ($formData->user?->name ? ($formData->user->name . $userPos) : 'Unknown user'),
            'status' => $formData->created_at ? 'completed' : 'pending',
            'date' => $formData->created_at ? $formData->created_at->toDateTimeString() : null,
            'details' => null,
        ];

        foreach ($approvedDetails as $detail) {
            $steps[] = [
                'key' => 'approver_' . $detail->id,
                'label' => $this->approverPositionLabel($detail),
                'level' => $detail->level,
                'status' => $detail->status ?? 'pending',
                'comment' => $detail->comment ?? null,
                'date' => $detail->approved_date ? (is_string($detail->approved_date) ? Carbon::parse($detail->approved_date)->toDateTimeString() : $detail->approved_date->toDateTimeString()) : null,
                'nik' => $detail->approver,
            ];
        }

        if (in_array($formData->status, ['Approved', 'Rejected'])) {
            $steps[] = [
                'key' => 'final',
                'label' => 'Completed',
                'status' => $formData->status === 'Approved' ? 'completed' : 'rejected',
                'date' => $formData->updated_at ? $formData->updated_at->toDateTimeString() : null,
                'details' => null,
            ];
        }

        $activities = \Spatie\Activitylog\Models\Activity::with('causer')->where('subject_type', PreMpdrForm::class)
            ->where('subject_id', $formData->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $history = $activities->map(function ($act) {
            return [
                'id' => $act->id,
                'actor' => $act->causer ? ($act->causer->name ?? $act->causer->id) : 'System',
                'event' => $act->description ?? $act->event ?? null,
                'properties' => $act->properties ?? [],
                'time' => $act->created_at->toDateTimeString(),
            ];
        })->values();

        $response = $formData->toArray();
        $response['approved_detail_ordered'] = $approvedDetails->toArray();
        $response['approval_tracker'] = [
            'steps' => $steps,
            'total_approvers' => $totalApprovers,
            'processed_count' => $processedCount,
            'progress_percent' => $totalApprovers > 0 ? round(($processedCount / $totalApprovers) * 100) : 0,
            'current_status' => $formData->status,
        ];
        $response['history'] = $history;

        return $response;
    }
}
