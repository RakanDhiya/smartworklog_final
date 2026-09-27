<?php

namespace App\Policies;

use App\Models\User;

class ActivityTypePolicy
{
    /**
     * Semua user yang login boleh melihat daftar activity type
     * (dibutuhkan untuk dropdown saat membuat Activity), tapi
     * hanya Admin yang bisa kelola (create/update/delete) —
     * activity_types adalah data master, bukan modul kerja harian.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user): bool
    {
        return $user->hasRole('admin');
    }
}