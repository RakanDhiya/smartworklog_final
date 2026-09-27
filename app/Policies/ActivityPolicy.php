<?php

namespace App\Policies;

use App\Models\Activity;
use App\Models\User;

class ActivityPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('activities.view');
    }

    // Object-level: boleh lihat activity kalau itu miliknya sendiri, ATAU dia admin, ATAU dia terlibat di case yang sama dengan activity itu
    public function view(User $user, Activity $activity): bool
    {
        if (! $user->can('activities.view')) {
            return false;
        }

        if ($user->hasRole('admin') || $activity->user_id === $user->id) {
            return true;
        }

        if ($activity->case_id) {
            return $activity->case->pic_user_id === $user->id
                || $activity->case->team()->where('users.id', $user->id)->exists();
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->can('activities.create');
    }

    // Update HANYA boleh oleh pemilik activity itu sendiri.
    // Admin tidak diberi akses edit activity orang lain by default
    public function update(User $user, Activity $activity): bool
    {
        return $user->can('activities.update') && $activity->user_id === $user->id;
    }

    public function delete(User $user, Activity $activity): bool
    {
        return $user->can('activities.delete') && $activity->user_id === $user->id;
    }
}