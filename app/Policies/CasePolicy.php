<?php

namespace App\Policies;

use App\Models\LegalCase;
use App\Models\User;

class CasePolicy
{
    /**
     * List case: permission role-level. Filtering "case mana saja yang
     * boleh dilihat" (admin=semua, lawyer/staff=miliknya) dilakukan
     * di CaseService::paginate(), bukan di sini — supaya list tidak
     * membocorkan keberadaan case yang bukan haknya sama sekali.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('cases.view');
    }

    /**
     * Object-level: mencegah user membuka /cases/999 langsung via URL
     * hanya karena tahu ID-nya (Phase 1 poin 6).
     */
    public function view(User $user, LegalCase $case): bool
    {
        if (! $user->can('cases.view')) {
            return false;
        }

        if ($user->hasRole('admin')) {
            return true;
        }

        return $this->isInvolved($user, $case);
    }

    public function create(User $user): bool
    {
        return $user->can('cases.create');
    }

    /**
     * Update case hanya boleh oleh Admin, atau PIC/anggota tim case itu sendiri.
     * Permission 'cases.update' saja tidak cukup — harus juga terlibat di case-nya.
     */
    public function update(User $user, LegalCase $case): bool
    {
        if (! $user->can('cases.update')) {
            return false;
        }

        if ($user->hasRole('admin')) {
            return true;
        }

        return $this->isInvolved($user, $case);
    }

    /**
     * Delete case: sesuai Authorization Matrix Phase 1, hanya Admin
     * yang punya permission 'cases.delete' di seeder — jadi cek
     * permission saja sudah cukup merepresentasikan aturan ini.
     */
    public function delete(User $user, LegalCase $case): bool
    {
        return $user->can('cases.delete');
    }

    public function restore(User $user, LegalCase $case): bool
    {
        return $user->can('cases.delete');
    }

    public function forceDelete(User $user, LegalCase $case): bool
    {
        return false; // legal-sensitive record, tidak boleh hard delete
    }

    /**
     * Khusus untuk mengelola Team/Assignment case (dipakai Step 3).
     */
    public function assign(User $user, LegalCase $case): bool
    {
        return $user->can('cases.assign');
    }

    protected function isInvolved(User $user, LegalCase $case): bool
    {
        if ($case->pic_user_id === $user->id) {
            return true;
        }

        return $case->team()->where('users.id', $user->id)->exists();
    }
}