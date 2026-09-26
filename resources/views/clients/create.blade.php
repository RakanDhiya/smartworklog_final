<x-layouts.app title="Tambah Client">
    <h1 class="text-lg font-semibold text-gray-800 mb-6">Tambah Client</h1>

    <div class="bg-white rounded-lg shadow-sm p-6 max-w-xl">
        <form method="POST" action="{{ route('clients.store') }}" class="space-y-4">
            @csrf
            @include('clients._form')

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-md">
                Simpan
            </button>
        </form>
    </div>
</x-layouts.app>