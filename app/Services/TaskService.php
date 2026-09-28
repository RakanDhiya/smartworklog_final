<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

class TaskService
{
    public const STATUSES = ['Todo', 'In Progress', 'Completed', 'Cancelled'];

    public const PRIORITIES = ['Low', 'Medium', 'High', 'Urgent'];

    // List task sesuai scope:
    // - Admin: semua task.
    // - Pengelola task (punya tasks.create, mis. Lawyer): task miliknya + task pada case yang dia (PIC/anggota tim).
    // - Lainnya (mis. Staff): hanya task yang di-assign kepadanya.

    // Urutan: task terbuka dulu (due date terdekat di atas, tanpa due date di bawah), lalu task Completed/Cancelled.
    public function paginate(User $user, array $filters = []): LengthAwarePaginator
    {
        $query = Task::query()->with([
            'assignee:id,name',
            'case:id,case_number,title,pic_user_id',
            'case.team' => fn ($q) => $q->select('users.id'),
        ]);

        if (! $user->hasRole('admin')) {
            $query->where(function ($q) use ($user) {
                $q->where('assigned_to', $user->id);

                if ($user->can('tasks.create')) {
                    $q->orWhereHas('case', fn ($c) => $c->accessibleBy($user));
                }
            });
        }

        $query->when($filters['status'] ?? null, fn ($q, $v) => $q->where('status', $v));
        $query->when($filters['priority'] ?? null, fn ($q, $v) => $q->where('priority', $v));
        $query->when($filters['assigned_to'] ?? null, fn ($q, $v) => $q->where('assigned_to', $v));

        return $query
            ->orderByRaw("CASE WHEN status IN ('Completed', 'Cancelled') THEN 1 ELSE 0 END")
            ->orderByRaw('due_date ASC NULLS LAST')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();
    }

    public function create(array $data): Task
    {
        $task = new Task($data);
        $this->applyCompletion($task);
        $task->save();

        return $task;
    }

    public function update(Task $task, array $data): Task
    {
        $task->fill($data);
        $this->applyCompletion($task);
        $task->save();

        return $task;
    }

    public function changeStatus(Task $task, string $status): Task
    {
        $task->status = $status;
        $this->applyCompletion($task);
        $task->save();

        return $task;
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }

    // completed_date dikelola sistem, bukan input user: terisi (hari ini) saat status Completed, kosong untuk status lain.
    protected function applyCompletion(Task $task): void
    {
        if ($task->status === 'Completed') {
            if ($task->completed_date === null) {
                $task->completed_date = Carbon::today();
            }

            return;
        }

        $task->completed_date = null;
    }
}