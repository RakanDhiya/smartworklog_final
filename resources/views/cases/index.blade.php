<x-layouts.app title="Cases">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-lg font-semibold text-gray-800">Cases</h1>
        @can('create', App\Models\LegalCase::class)
            <a href="{{ route('cases.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-md">
                + Tambah Case
            </a>
        @endcan
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-md">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" class="mb-4 flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor/judul case..."
            class="rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">

        <select name="status" class="rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
            <option value="">Semua Status</option>
            @foreach (['Draft', 'Active', 'On Hold', 'Completed', 'Closed'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
            @endforeach
        </select>

        <select name="priority" class="rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
            <option value="">Semua Priority</option>
            @foreach (['Low', 'Medium', 'High', 'Urgent'] as $priority)
                <option value="{{ $priority }}" @selected(request('priority') === $priority)>{{ $priority }}</option>
            @endforeach
        </select>

        <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm px-4 py-2 rounded-md">
            Filter
        </button>
    </form>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-left">
                <tr>
                    <th class="px-4 py-3">Case Number</th>
                    <th class="px-4 py-3">Judul</th>
                    <th class="px-4 py-3">Client</th>
                    <th class="px-4 py-3">PIC</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Priority</th>
                    <th class="px-4 py-3">Deadline</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($cases as $case)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $case->case_number }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $case->title }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $case->client->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $case->pic->name }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-700">{{ $case->status }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $case->priority }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $case->deadline?->format('d M Y') ?? '-' }}</td>
                        <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                            <a href="{{ route('cases.show', $case) }}" class="text-gray-600 hover:underline">Detail</a>
                            @can('update', $case)
                                <a href="{{ route('cases.edit', $case) }}" class="text-blue-600 hover:underline">Edit</a>
                            @endcan
                            @can('delete', $case)
                                <form method="POST" action="{{ route('cases.destroy', $case) }}" class="inline"
                                    onsubmit="return confirm('Hapus case {{ $case->case_number }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-400">Belum ada case.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $cases->links() }}
    </div>
</x-layouts.app>