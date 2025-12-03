<?php

namespace App\Http\Controllers;

use App\Models\PREMPDR\PreMpdrForm;
use App\Models\MPDR\MpdrForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        // ==================
        //  1. Query Dasar Sesuai Hak Akses
        // ==================
        $preMpdrQuery = PreMpdrForm::query();
        $mpdrQuery = MpdrForm::query();

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
        $allPreMpdr = $preMpdrQuery->clone()->get();
        $allMpdr = $mpdrQuery->clone()->with(['user.department'])->get(); // Eager load user & department

        // ==================
        //  2. Hitung Metrik Box
        // ==================
        $preMpdrOutstanding = $allPreMpdr->whereIn('status', ['Draft', 'In Approval'])->count();
        $mpdrOutstanding = $allMpdr->where('status', 'In Approval')->count();
        $highPriority = $allMpdr->where('status', 'In Approval')->whereIn('level_priority', ['A'])->count();

        // Hitung 'Butuh Persetujuan Saya'
        $needsMyApprovalPre = $isAdmin ? 0 : $allPreMpdr->filter(function($form) use ($user) {
            return $form->status == 'In Approval' && $form->approvedDetail->where('approver', $user->nik)->whereNull('status')->isNotEmpty();
        })->count();

        $needsMyApprovalMpdr = $isAdmin ? 0 : $allMpdr->filter(function($form) use ($user) {
            if ($form->status !== 'In Approval') return false;
            $isNextApprover = $form->approvedDetail->where('approver_nik', $user->nik)->where('status', 'pending')->isNotEmpty();
            $isNextInitiator = optional($form->initiatorDetail)->initiator_nik == $user->nik && $form->initiatorDetail->status == 'pending';
            return $isNextApprover || $isNextInitiator;
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
        $top5Products = $mpdrQuery->clone()->with('approvedDetail')->where('status', 'In Approval')
            ->orderBy('created_at', 'desc')->take(5)->get()->map(function($form){
                return [
                    'no' => $form->no,
                    'product_name' => $form->product_name,
                    'approved_count' => $form->approvedDetail->whereNotIn('status', ['pending', 'Vacant', 'not approve'])->count(),
                    'total_approvers' => $form->approvedDetail->where('status', '!=', 'Vacant')->count(),
                ];
            });

        $top5RequestersQuery = MpdrForm::with('user.department')
            ->select('user_id', DB::raw('count(*) as forms_count'))
            ->groupBy('user_id')
            ->orderBy('forms_count', 'desc')
            ->take(5);

        $top5Requesters = $top5RequestersQuery->get();
        $maxFormsCount = $top5Requesters->first()->forms_count ?? 0; // Ambil jumlah form terbanyak

        $top5RequestersData = $top5Requesters->map(function ($item) {
            return [
                'user_name' => optional($item->user)->name,
                'department' => optional(optional($item->user)->department)->name,
                'forms_count' => $item->forms_count,
            ];
        });

        // ==================
        //  5. Kembalikan Semua Data
        // ==================
        return response()->json([
            'metrics' => [
                'preMpdrOutstanding' => $preMpdrOutstanding,
                'mpdrOutstanding' => $mpdrOutstanding,
                'needsMyApproval' => $needsMyApprovalPre + $needsMyApprovalMpdr,
                'highPriority' => $highPriority,
            ],
            'chart' => $chartData,
            'top5' => [
                'products' => $top5Products,
                'requesters' => [
                    'list' => $top5RequestersData,
                    'max_count' => $maxFormsCount,
                ],
            ],
        ]);
    }
}
