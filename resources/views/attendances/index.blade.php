<x-layouts.app title="Attendance">
    <h1 class="text-lg font-semibold text-gray-800 mb-6">Attendance</h1>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-md">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-md">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-md">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- Absensi hari ini --}}
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-3">Absensi Hari Ini</h2>

        @if ($todayAttendance)
            <div class="flex flex-wrap items-center gap-6 text-sm">
                <div>
                    <span class="text-gray-500">Check In:</span>
                    <span class="font-medium text-gray-800">{{ $todayAttendance->check_in?->format('H:i') ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Check Out:</span>
                    <span class="font-medium text-gray-800">{{ $todayAttendance->check_out?->format('H:i') ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-700">{{ $todayAttendance->status }}</span>
                </div>
                @if (! $todayAttendance->check_out)
                    <form method="POST" action="{{ route('attendances.checkout') }}">
                        @csrf
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-md">
                            Check Out
                        </button>
                    </form>
                @endif
            </div>
        @else
            <form method="POST" action="{{ route('attendances.checkin') }}">
                @csrf
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-md">
                    Check In
                </button>
            </form>
        @endif
    </div>

    {{-- Filter periode --}}
    <form method="GET" class="mb-4 flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Dari</label>
            <input type="date" name="start" value="{{ $start->format('Y-m-d') }}"
                class="rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Sampai</label>
            <input type="date" name="end" value="{{ $end->format('Y-m-d') }}"
                class="rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Status</label>
            <select name="status" class="rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
                <option value="">Semua Status</option>
                @foreach (['Present', 'Late', 'Leave', 'Sick', 'Absent'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm px-4 py-2 rounded-md">Filter</button>
    </form>

    {{-- Statistik periode (dihitung di database) --}}
    <p class="text-xs text-gray-400 mb-2">
        Statistik periode {{ $start->format('d M Y') }} sampai {{ $end->format('d M Y') }}
        @cannot('attendances.manage_all')
            (milik Anda)
        @else
            (seluruh employee)
        @endcannot
    </p>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        @foreach ($statistics['by_status'] as $status => $total)
            <x-stat-card :label="$status" :value="$total" />
        @endforeach
        <x-stat-card label="Total Jam Kerja"
            :value="intdiv($statistics['work_minutes'], 60).' jam '.($statistics['work_minutes'] % 60).' mnt'" />
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-lg shadow-sm overflow-hidden overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-left">
                <tr>
                    <th class="px-4 py-3">Tanggal</th>
                    @can('attendances.manage_all')
                        <th class="px-4 py-3">Employee</th>
                    @endcan
                    <th class="px-4 py-3">Check In</th>
                    <th class="px-4 py-3">Check Out</th>
                    <th class="px-4 py-3">Durasi Kerja</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Catatan</th>
                    @can('attendances.manage_all')
                        <th class="px-4 py-3 text-right">Aksi</th>
                    @endcan
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($attendances as $attendance)
                    <tr>
                        <td class="px-4 py-3 text-gray-600">{{ $attendance->date->format('d M Y') }}</td>
                        @can('attendances.manage_all')
                            <td class="px-4 py-3 text-gray-600">{{ $attendance->user->name }}</td>
                        @endcan
                        <td class="px-4 py-3 text-gray-600">{{ $attendance->check_in?->format('H:i') ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $attendance->check_out?->format('H:i') ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                            @if (! is_null($attendance->work_minutes))
                                {{ intdiv($attendance->work_minutes, 60) }} jam {{ $attendance->work_minutes % 60 }} mnt
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-700">{{ $attendance->status }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $attendance->notes ?? '-' }}</td>
                        @can('attendances.manage_all')
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('attendances.edit', $attendance) }}" class="text-blue-600 hover:underline">Edit</a>
                            </td>
                        @endcan
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-400">Belum ada data attendance pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $attendances->links() }}
    </div>
</x-layouts.app>