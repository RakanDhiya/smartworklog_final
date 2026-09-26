@php
    // Normalisasi: di mode create, $case tidak pernah dikirim dari Controller.
    // Baris ini membuat $case terdefinisi sebagai null agar operator nullsafe (?->)
    // di bawah tidak menyebabkan "Undefined variable" saat method dipanggil pada tanggal.
    $case = $case ?? null;
@endphp

@if ($errors->any())
    <div class="p-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-md">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Case Number</label>
        <input type="text" name="case_number" value="{{ old('case_number', $case?->case_number ?? '') }}"
            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Case Type</label>
        <input type="text" name="case_type" value="{{ old('case_type', $case?->case_type ?? '') }}"
            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Judul</label>
    <input type="text" name="title" value="{{ old('title', $case?->title ?? '') }}"
        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
    <textarea name="description" rows="3"
        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">{{ old('description', $case?->description ?? '') }}</textarea>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Client</label>
        <select name="client_id" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
            <option value="">— Pilih Client —</option>
            @foreach ($clients as $client)
                <option value="{{ $client->id }}" @selected(old('client_id', $case?->client_id ?? '') == $client->id)>
                    {{ $client->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">PIC (Primary)</label>
        <select name="pic_user_id" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
            <option value="">— Pilih PIC —</option>
            @foreach ($picOptions as $pic)
                <option value="{{ $pic->id }}" @selected(old('pic_user_id', $case?->pic_user_id ?? '') == $pic->id)>
                    {{ $pic->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Status</label>
        <select name="status" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
            @foreach (['Draft', 'Active', 'On Hold', 'Completed', 'Closed'] as $status)
                <option value="{{ $status }}" @selected(old('status', $case?->status ?? 'Draft') === $status)>{{ $status }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Priority</label>
        <select name="priority" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
            @foreach (['Low', 'Medium', 'High', 'Urgent'] as $priority)
                <option value="{{ $priority }}" @selected(old('priority', $case?->priority ?? 'Medium') === $priority)>{{ $priority }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="grid grid-cols-3 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Start Date</label>
        <input type="date" name="start_date" value="{{ old('start_date', $case?->start_date?->format('Y-m-d') ?? '') }}"
            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Deadline</label>
        <input type="date" name="deadline" value="{{ old('deadline', $case?->deadline?->format('Y-m-d') ?? '') }}"
            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">End Date</label>
        <input type="date" name="end_date" value="{{ old('end_date', $case?->end_date?->format('Y-m-d') ?? '') }}"
            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
    </div>
</div>