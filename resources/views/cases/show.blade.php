<x-layouts.app title="Detail Case — {{ $case->case_number }}">
    <div x-data="{ tab: 'overview' }">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-lg font-semibold text-gray-800">{{ $case->case_number }} — {{ $case->title }}</h1>
                <p class="text-sm text-gray-500">Client: {{ $case->client->name }}</p>
            </div>
            @can('update', $case)
                <a href="{{ route('cases.edit', $case) }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2 rounded-md">
                    Edit Case
                </a>
            @endcan
        </div>

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

        <div class="border-b border-gray-200 mb-6">
            <nav class="flex gap-6 text-sm">
                <button @click="tab = 'overview'" :class="tab === 'overview' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500'"
                    class="pb-3 border-b-2 font-medium">Overview</button>
                <button @click="tab = 'team'" :class="tab === 'team' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500'"
                    class="pb-3 border-b-2 font-medium">Team</button>
                <button @click="tab = 'activities'" :class="tab === 'activities' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500'"
                    class="pb-3 border-b-2 font-medium">Activities</button>
                <button @click="tab = 'tasks'" :class="tab === 'tasks' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500'"
                    class="pb-3 border-b-2 font-medium">Tasks</button>
                <button @click="tab = 'documents'" :class="tab === 'documents' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500'"
                    class="pb-3 border-b-2 font-medium">Documents</button>
                <button @click="tab = 'schedule'" :class="tab === 'schedule' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500'"
                    class="pb-3 border-b-2 font-medium">Schedule</button>
                <button @click="tab = 'history'" :class="tab === 'history' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500'"
                    class="pb-3 border-b-2 font-medium">Status History</button>
            </nav>
        </div>

        {{-- OVERVIEW --}}
        <div x-show="tab === 'overview'" class="bg-white rounded-lg shadow-sm p-6 grid grid-cols-2 gap-6 text-sm">
            <div>
                <p class="text-gray-500">Case Type</p>
                <p class="font-medium text-gray-800">{{ $case->case_type }}</p>
            </div>
            <div>
                <p class="text-gray-500">Status</p>
                <p class="font-medium text-gray-800">{{ $case->status }}</p>
            </div>
            <div>
                <p class="text-gray-500">Priority</p>
                <p class="font-medium text-gray-800">{{ $case->priority }}</p>
            </div>
            <div>
                <p class="text-gray-500">PIC (Primary)</p>
                <p class="font-medium text-gray-800">{{ $case->pic->name }}</p>
            </div>
            <div>
                <p class="text-gray-500">Start Date</p>
                <p class="font-medium text-gray-800">{{ $case->start_date?->format('d M Y') ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Deadline</p>
                <p class="font-medium text-gray-800">{{ $case->deadline?->format('d M Y') ?? '-' }}</p>
            </div>
            <div class="col-span-2">
                <p class="text-gray-500">Deskripsi</p>
                <p class="font-medium text-gray-800">{{ $case->description ?? '-' }}</p>
            </div>
            <div class="col-span-2 text-xs text-gray-400">
                Dibuat oleh {{ $case->creator->name }} pada {{ $case->created_at->format('d M Y H:i') }}
            </div>
        </div>

        {{-- TEAM --}}
        <div x-show="tab === 'team'" class="bg-white rounded-lg shadow-sm p-6">
            <table class="w-full text-sm mb-6">
                <thead class="text-gray-500 text-left border-b border-gray-100">
                    <tr>
                        <th class="py-2">Nama</th>
                        <th class="py-2">Role in Case</th>
                        <th class="py-2">Bergabung Sejak</th>
                        <th class="py-2 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($case->team as $member)
                        <tr>
                            <td class="py-2 font-medium text-gray-800">
                                {{ $member->name }}
                                @if ($member->id === $case->pic_user_id)
                                    <span class="text-xs bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full ml-1">PIC</span>
                                @endif
                            </td>
                            <td class="py-2 text-gray-600">{{ $member->pivot->role_in_case }}</td>
                            <td class="py-2 text-gray-600">{{ \Illuminate\Support\Carbon::parse($member->pivot->assigned_at)->format('d M Y') }}</td>
                            <td class="py-2 text-right">
                                @can('assign', $case)
                                    @if ($member->id !== $case->pic_user_id)
                                        <form method="POST" action="{{ route('cases.team.destroy', [$case, $member]) }}"
                                            onsubmit="return confirm('Hapus {{ $member->name }} dari tim?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                        </form>
                                    @else
                                        <span class="text-gray-300 text-xs italic">PIC tidak dapat dihapus</span>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @can('assign', $case)
                <div class="border-t border-gray-100 pt-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Tambah Anggota Tim</h3>

                    @if ($errors->any())
                        <div class="mb-3 p-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-md">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('cases.team.store', $case) }}" class="flex gap-3">
                        @csrf
                        <select name="user_id" class="rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
                            <option value="">— Pilih User —</option>
                            @foreach ($availableUsers as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                        <select name="role_in_case" class="rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
                            <option value="Member">Member</option>
                            <option value="Support">Support</option>
                        </select>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-md">
                            Tambah
                        </button>
                    </form>
                </div>
            @endcan
        </div>

        {{-- ACTIVITIES --}}
        <div x-show="tab === 'activities'" class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-700">Activities terkait Case ini</h3>
                @can('create', App\Models\Activity::class)
                    <a href="{{ route('activities.create') }}" class="text-blue-600 hover:underline text-sm">
                        + Catat Activity Baru
                    </a>
                @endcan
            </div>

            <table class="w-full text-sm">
                <thead class="text-gray-500 text-left border-b border-gray-100">
                    <tr>
                        <th class="py-2">Tanggal</th>
                        <th class="py-2">Employee</th>
                        <th class="py-2">Tipe</th>
                        <th class="py-2">Deskripsi</th>
                        <th class="py-2">Durasi</th>
                        <th class="py-2 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($case->activities->sortByDesc('start_time') as $activity)
                        <tr>
                            <td class="py-2 text-gray-600 whitespace-nowrap">{{ $activity->start_time->format('d M Y H:i') }}</td>
                            <td class="py-2 text-gray-600">{{ $activity->user->name }}</td>
                            <td class="py-2 text-gray-600">{{ $activity->activityType->name }}</td>
                            <td class="py-2 text-gray-600 max-w-xs truncate">{{ $activity->description }}</td>
                            <td class="py-2 text-gray-600 whitespace-nowrap">{{ $activity->duration_minutes }} menit</td>
                            <td class="py-2 text-right">
                                @can('update', $activity)
                                    <a href="{{ route('activities.edit', $activity) }}" class="text-blue-600 hover:underline">Edit</a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-gray-400">Belum ada activity terkait case ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <p class="text-xs text-gray-400 mt-4">
                Total waktu tercatat: {{ $case->activities->sum('duration_minutes') }} menit
                ({{ number_format($case->activities->sum('duration_minutes') / 60, 1) }} jam)
            </p>
        </div>
        <div x-show="tab === 'tasks'" class="bg-white rounded-lg shadow-sm p-6 text-sm text-gray-400">
            Modul Tasks akan tersedia di Phase 7.
        </div>
        <div x-show="tab === 'documents'" class="bg-white rounded-lg shadow-sm p-6 text-sm text-gray-400">
            Modul Documents akan tersedia di Phase 9.
        </div>
        <div x-show="tab === 'schedule'" class="bg-white rounded-lg shadow-sm p-6 text-sm text-gray-400">
            Modul Schedule akan tersedia di Phase 8.
        </div>

        {{-- STATUS HISTORY --}}
        <div x-show="tab === 'history'" class="bg-white rounded-lg shadow-sm p-6">
            <ul class="space-y-3 text-sm">
                @forelse ($case->statusHistories as $history)
                    <li class="border-l-2 border-blue-200 pl-3">
                        <p class="font-medium text-gray-800">{{ $history->status }}</p>
                        <p class="text-gray-400 text-xs">
                            {{ $history->created_at->format('d M Y H:i') }} oleh {{ $history->changer->name }}
                        </p>
                        @if ($history->notes)
                            <p class="text-gray-600 mt-1">{{ $history->notes }}</p>
                        @endif
                    </li>
                @empty
                    <li class="text-gray-400">Belum ada riwayat status.</li>
                @endforelse
            </ul>
        </div>
    </div>
</x-layouts.app>