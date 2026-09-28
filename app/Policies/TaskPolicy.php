<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('tasks.view');
    }

    // Boleh lihat: Admin, assignee, atau pengelola task (punya tasks.create) yang terlibat di case task tersebut.
    public function view(User $user, Task $task): bool
    {
        if (! $user->can('tasks.view')) {
            return false;
        }

        return $user->hasRole('admin')
            || $task->assigned_to === $user->id
            || $this->managesViaCase($user, $task);
    }

    public function create(User $user): bool
    {
        return $user->can('tasks.create');
    }

    // Edit penuh: Admin, atau Lawyer yang terlibat di case task ini.
    // Assignee biasa (mis. Staff) TIDAK boleh edit penuh
    public function update(User $user, Task $task): bool
    {
        if (! $user->can('tasks.update')) {
            return false;
        }

        return $user->hasRole('admin') || $this->managesViaCase($user, $task);
    }

    // Ubah status saja: assignee task, atau siapa pun yang boleh edit penuh.
    public function updateStatus(User $user, Task $task): bool
    {
        if (! $user->can('tasks.update')) {
            return false;
        }

        return $task->assigned_to === $user->id || $this->update($user, $task);
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->can('tasks.delete');
    }

    protected function managesViaCase(User $user, Task $task): bool
    {
        return $user->can('tasks.create')
            && $task->case !== null
            && $task->case->involves($user);
    }
}