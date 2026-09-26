<?php

namespace App\Services;

use App\Models\User;

class DashboardService
{
    /**
     * Statistik untuk dashboard Admin — scope seluruh kantor.
     *
     * NOTE: Nilai di bawah ini PLACEHOLDER sementara.
     * Akan diganti query aggregation sungguhan begitu tabel terkait
     * dibangun: cases (Phase 4), activities (Phase 5),
     * attendances (Phase 6), tasks (Phase 7).
     *
     * Contoh query final nanti (JANGAN ambil semua row lalu hitung di PHP):
     *   LegalCase::where('status', 'Active')->count();
     *   Attendance::whereDate('date', today())->count();
     */
    public function getAdminStats(): array
    {
        return [
            'total_employees' => User::count(), // ini sudah query real, karena tabel users sudah ada
            'active_cases' => null,      // menunggu Phase 4
            'completed_cases' => null,   // menunggu Phase 4
            'today_attendance' => null,  // menunggu Phase 6
            'pending_tasks' => null,     // menunggu Phase 7
            'upcoming_schedules' => null,// menunggu Phase 8
        ];
    }

    /**
     * Statistik untuk dashboard Lawyer — scope milik sendiri / case yang diakses.
     */
    public function getLawyerStats(User $user): array
    {
        return [
            'my_cases' => null,         // menunggu Phase 4 (case_assignments)
            'my_activities' => null,    // menunggu Phase 5
            'today_attendance' => null, // menunggu Phase 6
            'my_tasks' => null,         // menunggu Phase 7
            'upcoming_schedule' => null,// menunggu Phase 8
            'work_hours' => null,       // menunggu Phase 5 (SUM duration_minutes)
        ];
    }

    /**
     * Statistik untuk dashboard Staff — scope lebih terbatas dari Lawyer.
     */
    public function getStaffStats(User $user): array
    {
        return [
            'assigned_cases' => null,
            'my_activities' => null,
            'my_attendance' => null,
            'my_tasks' => null,
            'upcoming_schedule' => null,
        ];
    }
}