<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;

class ClientPolicy
{
    /**
     * Client tidak punya konsep "ownership" seperti Case (tidak ada PIC/assignment).
     * Authorization di sini murni berbasis permission (role-level),
     * karena semua Lawyer/Staff yang punya akses modul ini boleh lihat semua client —
     * sesuai Authorization Matrix Phase 1 ("View saja" untuk Lawyer/Staff).
     */
    public function viewAny(User $user): bool
    {
        return $user->can('clients.view');
    }

    public function view(User $user, Client $client): bool
    {
        return $user->can('clients.view');
    }

    public function create(User $user): bool
    {
        return $user->can('clients.create');
    }

    public function update(User $user, Client $client): bool
    {
        return $user->can('clients.update');
    }

    public function delete(User $user, Client $client): bool
    {
        return $user->can('clients.delete');
    }

    public function restore(User $user, Client $client): bool
    {
        return $user->can('clients.delete');
    }

    /**
     * Force delete tidak diizinkan — client adalah legal-sensitive record
     * (Phase 1 poin 59: tidak boleh hard delete sembarangan).
     */
    public function forceDelete(User $user, Client $client): bool
    {
        return false;
    }
}