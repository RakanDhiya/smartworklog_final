<?php

namespace App\Services;

use App\Models\User;

class DashboardService
{
    public function __construct(
        protected AttendanceService $attendanceService
    ) {}

    // Statistik untuk dashboard Admin (scope seluruh kantor).
    // NOTE: nilai null = menunggu modul terkait (Phase 4 case stats,
    public function getAdminStats(): array
    {
        return [
            'total_employees' => User::count(),
            'active_cases' => null,
            'completed_cases' => null,
            'today_attendance' => $this->attendanceService->todayPresentCount(),
            'pending_tasks' => null,
            'upcoming_schedules' => null,
        ];
    }

    public function getLawyerStats(User $user): array
    {
        return [
            'my_cases' => null,
            'my_activities' => null,
            'today_attendance' => $this->todayAttendanceLabel($user),
            'my_tasks' => null,
            'upcoming_schedule' => null,
            'work_hours' => null,
        ];
    }

    public function getStaffStats(User $user): array
    {
        return [
            'assigned_cases' => null,
            'my_activities' => null,
            'my_attendance' => $this->todayAttendanceLabel($user),
            'my_tasks' => null,
            'upcoming_schedule' => null,
        ];
    }

    protected function todayAttendanceLabel(User $user): string
    {
        $attendance = $this->attendanceService->todayStatus($user);

        if (! $attendance) {
            return 'Belum check-in';
        }

        return $attendance->status.' ('.($attendance->check_in?->format('H:i') ?? '-').')';
    }
}