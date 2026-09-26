<x-layouts.app title="Clients">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-lg font-semibold text-gray-800">Clients</h1>
        @can('create', App\Models\Client::class)
            <a href="{{ route('clients.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-md">
                + Tambah Client
            </a>
        @endcan
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-md">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" class="mb-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama client..."
            class="w-full max-w-sm rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
    </form>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-left">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Tipe</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Telepon</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($clients as $client)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $client->name }}</td>
                        <td class="px-4 py-3 text-gray-600 capitalize">{{ $client->client_type }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $client->email ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $client->phone ?? '-' }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            @can('update', $client)
                                <a href="{{ route('clients.edit', $client) }}" class="text-blue-600 hover:underline">Edit</a>
                            @endcan
                            @can('delete', $client)
                                <form method="POST" action="{{ route('clients.destroy', $client) }}" class="inline"
                                    onsubmit="return confirm('Hapus client {{ $client->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-400">Belum ada data client.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $clients->links() }}
    </div>
</x-layouts.app>