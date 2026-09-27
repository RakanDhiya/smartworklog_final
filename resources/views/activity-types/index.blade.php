<x-layouts.app title="Activity Types">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-lg font-semibold text-gray-800">Activity Types</h1>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-left">
                    <tr>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Deskripsi</th>
                        @can('update', App\Models\ActivityType::class)
                            <th class="px-4 py-3 text-right">Aksi</th>
                        @endcan
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($activityTypes as $type)
                        <tr x-data="{ editing: false }">
                            <td class="px-4 py-3 font-medium text-gray-800" x-show="!editing">{{ $type->name }}</td>
                            <td class="px-4 py-3 text-gray-600" x-show="!editing">{{ $type->description ?? '-' }}</td>

                            <td colspan="2" x-show="editing" x-cloak class="px-4 py-3">
                                <form method="POST" action="{{ route('activity-types.update', $type) }}" class="flex gap-2 items-start">
                                    @csrf @method('PUT')
                                    <input type="text" name="name" value="{{ $type->name }}"
                                        class="rounded-md border border-gray-300 px-2 py-1 text-sm w-32">
                                    <input type="text" name="description" value="{{ $type->description }}"
                                        class="rounded-md border border-gray-300 px-2 py-1 text-sm flex-1">
                                    <button type="submit" class="text-blue-600 text-sm hover:underline">Simpan</button>
                                    <button type="button" @click="editing = false" class="text-gray-400 text-sm hover:underline">Batal</button>
                                </form>
                            </td>

                            @can('update', App\Models\ActivityType::class)
                                <td class="px-4 py-3 text-right space-x-2" x-show="!editing">
                                    <button @click="editing = true" class="text-blue-600 hover:underline text-sm">Edit</button>
                                    <form method="POST" action="{{ route('activity-types.destroy', $type) }}" class="inline"
                                        onsubmit="return confirm('Hapus activity type {{ $type->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-sm">Hapus</button>
                                    </form>
                                </td>
                            @endcan
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-gray-400">Belum ada activity type.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @can('create', App\Models\ActivityType::class)
            <div class="bg-white rounded-lg shadow-sm p-6 h-fit">
                <h2 class="text-sm font-semibold text-gray-700 mb-4">Tambah Activity Type</h2>
                <form method="POST" action="{{ route('activity-types.store') }}" class="space-y-3">
                    @csrf
                    @include('activity-types._form')
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-md">
                        Simpan
                    </button>
                </form>
            </div>
        @endcan
    </div>
</x-layouts.app>