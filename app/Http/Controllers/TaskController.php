<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Models\LegalCase;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Task::class);

        $filters = $request->validate([
            'status' => ['nullable', Rule::in(TaskService::STATUSES)],
            'priority' => ['nullable', Rule::in(TaskService::PRIORITIES)],
            'assigned_to' => ['nullable', 'integer'],
        ]);

        $user = $request->user();

        return view('tasks.index', [
            'tasks' => $this->taskService->paginate($user, $filters),
            // Dropdown filter assignee hanya untuk pengelola task.
            'assignees' => $user->can('tasks.create')
                ? User::orderBy('name')->get(['id', 'name'])
                : new Collection,
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Task::class);

        return view('tasks.create', $this->formData($request->user()));
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $this->taskService->create($request->validated());

        return redirect()->route('tasks.index')->with('success', 'Task berhasil dibuat.');
    }

    public function edit(Request $request, Task $task): View
    {
        $this->authorize('update', $task);

        return view('tasks.edit', ['task' => $task] + $this->formData($request->user()));
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $this->taskService->update($task, $request->validated());

        return redirect()->route('tasks.index')->with('success', 'Task berhasil diperbarui.');
    }

    public function updateStatus(UpdateTaskStatusRequest $request, Task $task): RedirectResponse
    {
        $this->taskService->changeStatus($task, $request->validated('status'));

        return redirect()->back()->with('success', 'Status task diperbarui.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);

        $this->taskService->delete($task);

        return redirect()->route('tasks.index')->with('success', 'Task berhasil dihapus.');
    }

    protected function formData(User $user): array
    {
        return [
            'cases' => LegalCase::accessibleBy($user)
                ->orderBy('case_number')
                ->get(['id', 'case_number', 'title']),
            'assignees' => User::where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name']),
        ];
    }
}