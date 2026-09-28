<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;

class AttendancePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('attendances.view');
    }

    // Object-level: lihat attendance sendiri, ATAU admin lihat siapa saja
    public function view(User $user, Attendance $attendance): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $attendance->user_id === $user->id;
    }

    //Check-in/check-out hanya untuk diri sendiri
    public function checkInOut(User $user): bool
    {
        return $user->can('attendances.manage_own');
    }

    // Update manual (ubah status jadi Leave/Sick/Absent, atau koreksi data) yang boleh hanya Admin — sesuai Authorization Matrix Phase 1 ("Admin: Full + semua user" untuk Attendance)
    public function update(User $user, Attendance $attendance): bool
    {
        return $user->can('attendances.manage_all');
    }
}