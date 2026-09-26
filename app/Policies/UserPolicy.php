<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Admin dapat melihat daftar semua user (Employee Management).
     */
    public function viewAny(User $user): bool
    {
        return $user->can('employees.view');
    }

    /**
     * Object-level: Admin boleh melihat siapa saja.
     * User biasa (lawyer/staff) hanya boleh melihat profilnya sendiri.
     */
    public function view(User $user, User $targetUser): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->id === $targetUser->id;
    }

    /**
     * Object-level: Admin boleh update siapa saja (termasuk ganti role).
     * User biasa hanya boleh update profilnya sendiri.
     */
    public function update(User $user, User $targetUser): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->id === $targetUser->id;
    }

    /**
     * Hanya Admin yang boleh menghapus (soft delete) user.
     * Admin TIDAK boleh menghapus akunnya sendiri — mencegah
     * situasi tidak ada admin aktif tersisa di sistem.
     */
    public function delete(User $user, User $targetUser): bool
    {
        if (! $user->hasRole('admin')) {
            return false;
        }

        return $user->id !== $targetUser->id;
    }

    /**
     * Hanya Admin yang boleh mengaktifkan kembali user yang di-soft-delete.
     */
    public function restore(User $user, User $targetUser): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Force delete (hapus permanen) TIDAK diizinkan siapa pun secara default.
     * Sesuai prinsip Phase 1: legal-sensitive records tidak boleh hard delete
     * sembarangan. Kalau nanti benar-benar dibutuhkan, harus lewat proses
     * khusus di luar Policy standar ini — bukan keputusan diam-diam di sini.
     */
    public function forceDelete(User $user, User $targetUser): bool
    {
        return false;
    }
}