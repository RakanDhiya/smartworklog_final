<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

class AttendanceService
{
    public const STATUSES = ['Present', 'Late', 'Leave', 'Sick', 'Absent'];

    // List attendance dalam satu periode, sesuai scope:
    // - Admin: semua user
    // - Lawyer/Staff: miliknya sendiri saja
    public function paginate(User $user, Carbon $start, Carbon $end, ?string $status = null): LengthAwarePaginator
    {
        return $this->baseQuery($user, $start, $end)
            ->with('user:id,name')
            ->when($status, fn ($q, $status) => $q->where('status', $status))
            ->orderByDesc('date')
            ->paginate(20)
            ->withQueryString();
    }

    // Statistik periode, dihitung di database (COUNT + GROUP BY, SUM), bukan dengan mengambil semua row lalu menghitungnya di PHP
    public function statistics(User $user, Carbon $start, Carbon $end): array
    {
        $base = $this->baseQuery($user, $start, $end);

        $counts = (clone $base)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $byStatus = [];
        foreach (self::STATUSES as $status) {
            $byStatus[$status] = (int) ($counts[$status] ?? 0);
        }

        // Total detik kerja = SUM(check_out - check_in) untuk record yang sudah check-out.
        $workSeconds = (clone $base)
            ->whereNotNull('check_in')
            ->whereNotNull('check_out')
            ->selectRaw('COALESCE(SUM(EXTRACT(EPOCH FROM (check_out - check_in))), 0) as seconds')
            ->value('seconds');

        return [
            'by_status' => $byStatus,
            'work_minutes' => (int) round(((float) $workSeconds) / 60),
        ];
    }

    // Jumlah employee yang hadir (Present + Late) hari ini, untuk dashboard Admin
    public function todayPresentCount(): int
    {
        return Attendance::query()
            ->whereDate('date', Carbon::today())
            ->whereIn('status', ['Present', 'Late'])
            ->count();
    }

    // Check-in untuk hari ini. Status otomatis Present/Late berdasarkan config('worklog.attendance.late_threshold')
    public function checkIn(User $user): Attendance
    {
        $today = Carbon::today();

        if (Attendance::where('user_id', $user->id)->whereDate('date', $today)->exists()) {
            throw new \InvalidArgumentException('Anda sudah melakukan check-in hari ini.');
        }

        $now = Carbon::now();
        $threshold = Carbon::createFromTimeString(config('worklog.attendance.late_threshold'));

        $status = $now->format('H:i:s') > $threshold->format('H:i:s') ? 'Late' : 'Present';

        return Attendance::create([
            'user_id' => $user->id,
            'date' => $today,
            'check_in' => $now,
            'status' => $status,
        ]);
    }

    /**
     * @throws \InvalidArgumentException kalau belum check-in atau sudah check-out.
     */
    public function checkOut(User $user): Attendance
    {
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', Carbon::today())
            ->first();

        if (! $attendance) {
            throw new \InvalidArgumentException('Anda belum melakukan check-in hari ini.');
        }

        if ($attendance->check_out) {
            throw new \InvalidArgumentException('Anda sudah melakukan check-out hari ini.');
        }

        $attendance->update(['check_out' => Carbon::now()]);

        return $attendance;
    }

    public function update(Attendance $attendance, array $data): Attendance
    {
        $attendance->update($data);

        return $attendance;
    }

    public function todayStatus(User $user): ?Attendance
    {
        return Attendance::where('user_id', $user->id)
            ->whereDate('date', Carbon::today())
            ->first();
    }

    // Query dasar: filter periode + scope role. Dipakai bersama oleh paginate() dan statistics() supaya angka statistik SELALU konsisten dengan isi tabel
    protected function baseQuery(User $user, Carbon $start, Carbon $end)
    {
        $query = Attendance::query()
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()]);

        if (! $user->hasRole('admin')) {
            $query->where('user_id', $user->id);
        }

        return $query;
    }
}