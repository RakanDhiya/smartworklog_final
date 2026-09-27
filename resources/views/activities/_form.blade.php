@php
    $activity = $activity ?? null;
@endphp

@if ($errors->any())
    <div class="p-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-md">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<div>
    <label class="block text-sm font-medium text-gray-700">Case (opsional)</label>
    <select name="case_id" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
        <option value="">— Tidak terkait case —</option>
        @foreach ($cases as $case)
            <option value="{{ $case->id }}" @selected(old('case_id', $activity?->case_id ?? '') == $case->id)>
                {{ $case->case_number }} — {{ $case->title }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Activity Type</label>
    <select name="activity_type_id" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
        <option value="">— Pilih Tipe —</option>
        @foreach ($activityTypes as $type)
            <option value="{{ $type->id }}" @selected(old('activity_type_id', $activity?->activity_type_id ?? '') == $type->id)>
                {{ $type->name }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
    <textarea name="description" rows="3"
        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">{{ old('description', $activity?->description ?? '') }}</textarea>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Waktu Mulai</label>
        <input type="datetime-local" name="start_time"
            value="{{ old('start_time', $activity?->start_time?->format('Y-m-d\TH:i') ?? '') }}"
            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Waktu Selesai</label>
        <input type="datetime-local" name="end_time"
            value="{{ old('end_time', $activity?->end_time?->format('Y-m-d\TH:i') ?? '') }}"
            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
    </div>
</div>

<p class="text-xs text-gray-400">
    Durasi dihitung otomatis dari selisih Waktu Mulai dan Waktu Selesai — tidak perlu diisi manual.
</p>

@if ($activity)
    <div class="text-sm text-gray-500 bg-gray-50 rounded-md p-3">
        Durasi saat ini: <span class="font-medium text-gray-700">{{ $activity->duration_minutes }} menit</span>
        (akan dihitung ulang otomatis setelah disimpan)
    </div>
@endif