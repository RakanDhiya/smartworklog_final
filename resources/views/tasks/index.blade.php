@php
    $statusStyles = [
        'Todo' => 'bg-gray-100 text-gray-700',
        'In Progress' => 'bg-blue-50 text-blue-700',
        'Completed' => 'bg-green-50 text-green-700',
        'Cancelled' => 'bg-gray-100 text-gray-400',
    ];
    $priorityStyles = [
        'Low' => 'text-gray-500',
        'Medium' => 'text-gray-700',
        'High' => 'text-orange-600 font-medium',
        'Urgent' => 'text-red-600 font-semibold',
    ];
@endphp

<x-layouts.app title="Tasks">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-lg font-semibold text-gray-800">Tasks</h1>
        @can('create', App\Models\Task::class)
            <a href="{{ route('tasks.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-md">
                + Tambah Task
            </a>
        @endcan
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-md">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-md">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="GET" class="mb-4 flex flex-wrap gap-3">
        <select name="status" class="rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
            <option value="">Semua Status</option>
            @foreach (['Todo', 'In Progress', 'Completed', 'Cancelled'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
            @endforeach
        </select>

        <select name="priority" class="rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
            <option value="">Semua Priority</option>
            @foreach (['Low', 'Medium', 'High', 'Urgent'] as $priority)
                <option value="{{ $priority }}" @selected(request('priority') === $priority)>{{ $priority }}</option>
            @endforeach
        </select>

        @if ($assignees->isNotEmpty())
            <select name="assigned_to" class="rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
                <option value="">Semua Assignee</option>
                @foreach ($assignees as $assignee)
                    <option value="{{ $assignee->id }}" @selected(request('assigned_to') == $assignee->id)>{{ $assignee->name }}</option>
                @endforeach
            </select>
        @endif

        <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm px-4 py-2 rounded-md">
            Filter
        </button>
    </form>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-left">
                <tr>
                    <th class="px-4 py-3">Judul</th>
                    <th class="px-4 py-3">Case</th>
                    <th class="px-4 py-3">Assigned To</th>
                    <th class="px-4 py-3">Priority</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Due Date</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($tasks as $task)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $task->title }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $task->case?->case_number ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $task->assignee->name }}</td>
                        <td class="px-4 py-3 {{ $priorityStyles[$task->priority] ?? '' }}">{{ $task->priority }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $statusStyles[$task->status] ?? '' }}">{{ $task->status }}</span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if ($task->due_date)
                                <span class="text-gray-600">{{ $task->due_date->format('d M Y') }}</span>
                                @if ($task->is_overdue)
                                    <span class="ml-1 text-xs px-2 py-0.5 rounded-full bg-red-50 text-red-700">Overdue</span>
                                @elseif ($task->is_due_soon)
                                    <span class="ml-1 text-xs px-2 py-0.5 rounded-full bg-amber-50 text-amber-700">Due Soon</span>
                                @endif
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                            @can('updateStatus', $task)
                                @if ($task->status === 'Todo')
                                    <form method="POST" action="{{ route('tasks.status', $task) }}" class="inline">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="In Progress">
                                        <button type="submit" class="text-blue-600 hover:underline">Mulai</button>
                                    </form>
                                @elseif ($task->status === 'In Progress')
                                    <form method="POST" action="{{ route('tasks.status', $task) }}" class="inline">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="Completed">
                                        <button type="submit" class="text-green-600 hover:underline">Selesai</button>
                                    </form>
                                @elseif ($task->status === 'Completed')
                                    <form method="POST" action="{{ route('tasks.status', $task) }}" class="inline">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="In Progress">
                                        <button type="submit" class="text-gray-500 hover:underline">Buka Kembali</button>
                                    </form>
                                @endif
                            @endcan
                            @can('update', $task)
                                <a href="{{ route('tasks.edit', $task) }}" class="text-blue-600 hover:underline">Edit</a>
                            @endcan
                            @can('delete', $task)
                                <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="inline"
                                    onsubmit="return confirm('Hapus task ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-400">Belum ada task.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $tasks->links() }}
    </div>
</x-layouts.app>