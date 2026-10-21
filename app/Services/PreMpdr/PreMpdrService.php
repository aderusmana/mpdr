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
        $query = PreMpdrForm::with(['approvedDetail' => function ($q) {
            $q->select(DB::raw("form_id, count(*) as total, sum(case when status is not null then 1 else 0 end) as approved_count"))
              ->groupBy('form_id');
        }]);

        if ($request->has('searchBuilder')) {
            $this->applySearchBuilder($query, $request->input('searchBuilder'));
        }

        $forms = $query->orderBy('created_at', 'desc')->get();

        if ($forms->isNotEmpty()) {
            $pendingApprovers = PreMpdrApprovedDetail::with('user.position')
                ->whereIn('form_id', $forms->pluck('id'))
                ->whereNull('status')
                ->orderBy('level')
                ->orderBy('id')
                ->get()
                ->groupBy('form_id');

            $forms->each(function (PreMpdrForm $form) use ($pendingApprovers) {
                if ($form->status !== 'In Approval') {
                    return;
                }

                $currentApprover = $pendingApprovers->get($form->id)?->first();
                if ($currentApprover) {
                    $form->route_to = $currentApprover->approver_position
                        ?: $currentApprover->user?->position?->name
                        ?: $form->route_to;
                }
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
        $forms = PreMpdrForm::with([
            'revision', 'detail', 'category', 'channel', 'description',
            'certification', 'competitor', 'packaging', 'market',
            'approvedDetail', 'user.position'
        ])
        ->whereIn('status', ['Approved', 'Rejected'])
        ->orderBy('created_at', 'desc')
        ->get();

        return $forms->map(function (PreMpdrForm $item) {
            $item->new_created_at = $item->created_at->format('j F Y');
            $item->submitted_by = $item->user ? $item->user->name : '-';
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
        $formData = PreMpdrForm::with([
            'revision', 'detail', 'category', 'channel', 'description',
            'certification', 'competitor', 'packaging', 'market',
            'user.position'
        ])->where('no', $formNo)->firstOrFail();

        $approvedDetails = $formData->approvedDetail()->orderBy('level', 'asc')->get();

        $totalApprovers = $approvedDetails->count();
        $processedCount = $approvedDetails->filter(function ($d) {
            return $d->status !== null && $d->status !== 'pending' && $d->status !== 'Vacant';
        })->count();

        $steps = [];
        $steps[] = [
            'key' => 'submitted',
            'label' => 'Submitted by ' . ($formData->user?->name ?? 'Unknown user'),
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

        $activities = \Spatie\Activitylog\Models\Activity::where('subject_type', PreMpdrForm::class)
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
