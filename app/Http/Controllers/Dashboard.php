<?php

namespace App\Http\Controllers;

use App\Models\PREMPDR\PreMpdrForm;
use App\Models\MPDR\MpdrForm;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Dashboard extends Controller
{
    public function mpdrDashboard()
{
    // Method ini hanya untuk menampilkan view-nya
    return view('dashboard');
    }

    public function getMpdrDashboardData()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $isAdmin = $user->hasRole('super-admin'); // Cukup cek satu role jika 'super-admin' mewarisi 'admin'
        $canViewPreMpdr = $user->can('view pre');
        $canViewMpdr = $user->can('view mpdr');

        // ==================
        //  1. Query Dasar Sesuai Hak Akses
        // ==================
        $preMpdrQuery = PreMpdrForm::query();
        $mpdrQuery = MpdrForm::query();

        if (!$canViewPreMpdr) {
            $preMpdrQuery->whereRaw('1 = 0');
        }

        if (!$canViewMpdr) {
            $mpdrQuery->whereRaw('1 = 0');
        }

        if (!$isAdmin) {
            $userNik = $user->nik;
            $userId = $user->id;

            // Filter Pre-MPDR: dibuat oleh user ATAU user adalah approver di form tsb
            $preMpdrQuery->where(function ($q) use ($userId, $userNik) {
                $q->where('user_id', $userId)
                ->orWhereHas('approvedDetail', fn($sub) => $sub->where('approver', $userNik));
            });

            // Filter MPDR: dibuat oleh user ATAU user adalah initiator/approver di form tsb
            $mpdrQuery->where(function ($q) use ($userId, $userNik) {
                $q->where('user_id', $userId)
                ->orWhereHas('initiatorDetail', fn($sub) => $sub->where('initiator_nik', $userNik))
                ->orWhereHas('approvedDetail', fn($sub) => $sub->where('approver_nik', $userNik));
            });
        }

        // Clone query untuk digunakan di berbagai kalkulasi
        $allPreMpdr = $preMpdrQuery->clone()->with(['user.department', 'approvedDetail.user.position', 'Mpdr'])->get();
        $allMpdr = $mpdrQuery->clone()->with([
            'user.department',
            'approvedDetail.user.position',
            'initiatorDetail',
        ])->get();

        // ==================
        //  2. Hitung Metrik Box
        // ==================
        $preMpdrOutstanding = $allPreMpdr->whereIn('status', ['Draft', 'In Approval'])->count();
        $mpdrOutstanding = $allMpdr->where('status', 'In Approval')->count();
        $highPriority = $allMpdr->where('status', 'In Approval')->whereIn('level_priority', ['A'])->count();
        $approvedPreMpdr = $allPreMpdr->where('status', 'Approved');
        $convertedPreMpdr = $approvedPreMpdr->filter(fn ($form) => $form->Mpdr !== null);
        $preMpdrConversionRate = $approvedPreMpdr->isEmpty()
            ? 0
            : round(($convertedPreMpdr->count() / $approvedPreMpdr->count()) * 100, 1);

        // Hitung 'Butuh Persetujuan Saya'
        $needsMyApprovalPre = $isAdmin ? 0 : $allPreMpdr->filter(function ($form) use ($user) {
            if ($form->status !== 'In Approval') {
                return false;
            }

            $nextApproval = $form->approvedDetail
                ->filter(fn ($detail) => $detail->status === null || strtolower(trim($detail->status)) === 'pending')
                ->sortBy([['level', 'asc'], ['id', 'asc']])
                ->first();

            return $nextApproval?->approver === $user->nik;
        })->count();

        $needsMyApprovalMpdr = $isAdmin ? 0 : $allMpdr->filter(function ($form) use ($user) {
            if ($form->status !== 'In Approval') {
                return false;
            }

            $initiator = $form->initiatorDetail;
            if ($initiator && strtolower(trim($initiator->status ?? '')) === 'pending') {
                return $initiator->initiator_nik === $user->nik;
            }

            $nextApproval = $form->approvedDetail
                ->filter(fn ($detail) => strtolower(trim($detail->status ?? '')) === 'pending')
                ->sortBy([['created_at', 'asc'], ['id', 'asc']])
                ->first();

            return $nextApproval?->approver_nik === $user->nik;
        })->count();

        // ==================
        //  3. Siapkan Data Grafik
        // ==================
        $chartData = [];
        $years = array_merge(
            $allPreMpdr->pluck('created_at')->map(fn($date) => $date->year)->unique()->toArray(),
            $allMpdr->pluck('created_at')->map(fn($date) => $date->year)->unique()->toArray()
        );
        $years = array_unique($years);
        rsort($years); // Urutkan dari tahun terbaru

        foreach (['prempdr', 'mpdr'] as $type) {
            $sourceData = ($type == 'prempdr') ? $allPreMpdr : $allMpdr;
            foreach ($years as $year) {
                $dataByMonth = $sourceData->where('created_at', '>=', "$year-01-01")->where('created_at', '<=', "$year-12-31")->groupBy(fn($date) => Carbon::parse($date->created_at)->format('n'));

                $created = array_fill(0, 12, 0);
                $approved = array_fill(0, 12, 0);
                $pending = array_fill(0, 12, 0);
                $rejected = array_fill(0, 12, 0);

                foreach($dataByMonth as $month => $items) {
                    $idx = $month - 1;
                    $created[$idx] = $items->count();
                    $approved[$idx] = $items->where('status', 'Approved')->count();
                    $rejected[$idx] = $items->where('status', 'Rejected')->count();
                    // Pending dihitung dari yang dibuat bulan itu dan statusnya masih 'In Approval'
                    $pending[$idx] = $items->where('status', 'In Approval')->count();
                }
                $chartData[$type][$year] = compact('created', 'approved', 'pending', 'rejected');
            }
        }

        // ==================
        //  4. Siapkan Data Top 5
        // ==================
        $buildTopProducts = function ($forms, $nameAttribute) {
            return $forms->where('status', 'In Approval')
                ->sortByDesc('created_at')
                ->take(5)
                ->map(function ($form) use ($nameAttribute) {
                    $approvedCount = $form->approvedDetail->filter(function ($detail) {
                        return in_array(strtolower(trim($detail->status ?? '')), ['approve', 'approved'], true);
                    })->count();
                    $totalApprovers = $form->approvedDetail->filter(function ($detail) {
                        return strtolower(trim($detail->status ?? '')) !== 'vacant';
                    })->count();

                    return [
                        'no' => $form->no,
                        'product_name' => $form->{$nameAttribute},
                        'approved_count' => $approvedCount,
                        'total_approvers' => $totalApprovers,
                    ];
                });
        };

        $buildTopRequesters = function ($forms) {
            $requesters = $forms->groupBy('user_id')
                ->map(function ($userForms) {
                    $user = $userForms->first()->user;

                    return [
                        'user_name' => $user?->name ?? 'Unknown',
                        'department' => $user?->department?->name,
                        'forms_count' => $userForms->count(),
                    ];
                })
                ->sortByDesc('forms_count')
                ->take(5)
                ->values();

            return [
                'list' => $requesters,
                'max_count' => $requesters->max('forms_count') ?? 0,
            ];
        };

        $approvalQueue = collect();
        foreach ($allPreMpdr->where('status', 'In Approval') as $form) {
            $pending = $form->approvedDetail
                ->filter(fn ($detail) => $detail->status === null || strtolower(trim($detail->status)) === 'pending')
                ->sortBy([['level', 'asc'], ['id', 'asc']])
                ->first();

            $approvalQueue->push([
                'workflow' => 'Pre-MPDR',
                'form_no' => $form->no,
                'project_name' => $form->project_name,
                'stage' => $form->route_to ?: ($pending?->approver_position ?: $pending?->name ?: 'Approval in progress'),
                'updated_at' => $form->updated_at,
                'days_since_update' => $form->updated_at?->diffInDays(now()) ?? 0,
            ]);
        }

        foreach ($allMpdr->where('status', 'In Approval') as $form) {
            $pending = $form->approvedDetail
                ->filter(fn ($detail) => strtolower(trim($detail->status ?? '')) === 'pending')
                ->sortBy([['created_at', 'asc'], ['id', 'asc']])
                ->first();
            $initiator = $form->initiatorDetail;
            $pendingInitiator = $initiator && strtolower(trim($initiator->status ?? '')) === 'pending';

            $approvalQueue->push([
                'workflow' => 'MPDR',
                'form_no' => $form->no,
                'project_name' => $form->product_name,
                'stage' => $pendingInitiator
                    ? $initiator->initiator_name
                    : ($pending?->approver_name ?: 'Approval in progress'),
                'updated_at' => $form->updated_at,
                'days_since_update' => $form->updated_at?->diffInDays(now()) ?? 0,
            ]);
        }

        $staleApprovals = $approvalQueue->where('days_since_update', '>=', 7)->count();
        $approvalQueue = $approvalQueue->sortByDesc('days_since_update')->take(8)->values();

        // ==================
        //  5. Kembalikan Semua Data
        // ==================
        return response()->json([
            'metrics' => [
                'preMpdrOutstanding' => $preMpdrOutstanding,
                'mpdrOutstanding' => $mpdrOutstanding,
                'needsMyApproval' => $needsMyApprovalPre + $needsMyApprovalMpdr,
                'highPriority' => $highPriority,
                'approvedPreMpdr' => $approvedPreMpdr->count(),
                'convertedPreMpdr' => $convertedPreMpdr->count(),
                'preMpdrConversionRate' => $preMpdrConversionRate,
                'staleApprovals' => $staleApprovals,
            ],
            'approvalQueue' => $approvalQueue,
            'chart' => $chartData,
            'top5' => [
                'prempdr' => [
                    'products' => $buildTopProducts($allPreMpdr, 'project_name')->values(),
                    'requesters' => $buildTopRequesters($allPreMpdr),
                ],
                'mpdr' => [
                    'products' => $buildTopProducts($allMpdr, 'product_name')->values(),
                    'requesters' => $buildTopRequesters($allMpdr),
                ],
            ],
        ]);
    }
}
