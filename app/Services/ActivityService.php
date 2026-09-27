<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

class ActivityService
{
    /**
     * List activity sesuai scope role:
     * - Admin: semua activity.
     * - Lawyer/Staff: activity miliknya sendiri, ATAU activity terkait
     *   case yang dia PIC/anggota tim.
     */
    public function paginate(User $user, array $filters = []): LengthAwarePaginator
    {
        $query = Activity::query()->with(['user:id,name', 'case:id,case_number,title', 'activityType:id,name']);

        if (! $user->hasRole('admin')) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhereHas('case', function ($q2) use ($user) {
                        $q2->where('pic_user_id', $user->id)
                            ->orWhereHas('team', fn ($q3) => $q3->where('users.id', $user->id));
                    });
            });
        }

        $query->when($filters['case_id'] ?? null, fn ($q, $caseId) => $q->where('case_id', $caseId));
        $query->when($filters['activity_type_id'] ?? null, fn ($q, $typeId) => $q->where('activity_type_id', $typeId));
        $query->when($filters['date'] ?? null, fn ($q, $date) => $q->whereDate('start_time', $date));

        return $query->orderByDesc('start_time')->paginate(20)->withQueryString();
    }

    /**
     * Buat activity baru. duration_minutes SELALU dihitung dari
     * end_time - start_time di sini — tidak pernah menerima input
     * manual dari form (sesuai keputusan final Phase 1).
     */
    public function create(array $data, User $user): Activity
    {
        return Activity::create([
            ...$data,
            'user_id' => $user->id,
            'duration_minutes' => $this->calculateDuration($data['start_time'], $data['end_time']),
        ]);
    }

    public function update(Activity $activity, array $data): Activity
    {
        $activity->update([
            ...$data,
            'duration_minutes' => $this->calculateDuration($data['start_time'], $data['end_time']),
        ]);

        return $activity;
    }

    public function delete(Activity $activity): void
    {
        $activity->delete();
    }

    protected function calculateDuration(string $startTime, string $endTime): int
    {
        return Carbon::parse($startTime)->diffInMinutes(Carbon::parse($endTime));
    }
}