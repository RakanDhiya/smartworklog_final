<x-layouts.app title="Activities">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-lg font-semibold text-gray-800">Activities / Worklog</h1>
        @can('create', App\Models\Activity::class)
            <a href="{{ route('activities.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-md">
                + Catat Activity
            </a>
        @endcan
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-md">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" class="mb-4 flex flex-wrap gap-3">
        <select name="case_id" class="rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
            <option value="">Semua Case</option>
            @foreach ($cases as $case)
                <option value="{{ $case->id }}" @selected(request('case_id') == $case->id)>{{ $case->case_number }} — {{ $case->title }}</option>
            @endforeach
        </select>

        <select name="activity_type_id" class="rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
            <option value="">Semua Tipe</option>
            @foreach ($activityTypes as $type)
                <option value="{{ $type->id }}" @selected(request('activity_type_id') == $type->id)>{{ $type->name }}</option>
            @endforeach
        </select>

        <input type="date" name="date" value="{{ request('date') }}"
            class="rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">

        <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm px-4 py-2 rounded-md">
            Filter
        </button>
    </form>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-left">
                <tr>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Employee</th>
                    <th class="px-4 py-3">Case</th>
                    <th class="px-4 py-3">Tipe</th>
                    <th class="px-4 py-3">Deskripsi</th>
                    <th class="px-4 py-3">Durasi</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($activities as $activity)
                    <tr>
                        <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $activity->start_time->format('d M Y H:i') }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $activity->user->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $activity->case?->case_number ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $activity->activityType->name }}</td>
                        <td class="px-4 py-3 text-gray-600 max-w-xs truncate">{{ $activity->description }}</td>
                        <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $activity->duration_minutes }} menit</td>
                        <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                            @can('update', $activity)
                                <a href="{{ route('activities.edit', $activity) }}" class="text-blue-600 hover:underline">Edit</a>
                            @endcan
                            @can('delete', $activity)
                                <form method="POST" action="{{ route('activities.destroy', $activity) }}" class="inline"
                                    onsubmit="return confirm('Hapus activity ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-400">Belum ada activity.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $activities->links() }}
    </div>
</x-layouts.app>