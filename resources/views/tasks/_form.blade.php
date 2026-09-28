@php
    $task = $task ?? null;
    $isAdmin = auth()->user()->hasRole('admin');
@endphp

@if ($errors->any())
    <div class="p-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-md">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<div>
    <label class="block text-sm font-medium text-gray-700">Judul</label>
    <input type="text" name="title" value="{{ old('title', $task?->title ?? '') }}"
        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
    <textarea name="description" rows="3"
        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">{{ old('description', $task?->description ?? '') }}</textarea>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">
            Case @unless ($isAdmin) <span class="text-red-500">*</span> @endunless
        </label>
        <select name="case_id" @required(! $isAdmin)
            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
            <option value="">{{ $isAdmin ? '— Tanpa case (tugas umum) —' : '— Pilih Case —' }}</option>
            @foreach ($cases as $case)
                <option value="{{ $case->id }}" @selected(old('case_id', $task?->case_id ?? '') == $case->id)>
                    {{ $case->case_number }} — {{ $case->title }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Assigned To</label>
        <select name="assigned_to" required
            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
            <option value="">— Pilih Assignee —</option>
            @foreach ($assignees as $assignee)
                <option value="{{ $assignee->id }}" @selected(old('assigned_to', $task?->assigned_to ?? '') == $assignee->id)>
                    {{ $assignee->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<p class="text-xs text-gray-400">
    Jika Case dipilih, assignee harus anggota tim case tersebut.
</p>

<div class="grid grid-cols-3 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Priority</label>
        <select name="priority" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
            @foreach (['Low', 'Medium', 'High', 'Urgent'] as $priority)
                <option value="{{ $priority }}" @selected(old('priority', $task?->priority ?? 'Medium') === $priority)>{{ $priority }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Status</label>
        <select name="status" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
            @foreach (['Todo', 'In Progress', 'Completed', 'Cancelled'] as $status)
                <option value="{{ $status }}" @selected(old('status', $task?->status ?? 'Todo') === $status)>{{ $status }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Due Date</label>
        <input type="date" name="due_date" value="{{ old('due_date', $task?->due_date?->format('Y-m-d') ?? '') }}"
            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
    </div>
</div>