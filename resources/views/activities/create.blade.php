<x-layouts.app title="Catat Activity">
    <h1 class="text-lg font-semibold text-gray-800 mb-6">Catat Activity</h1>

    <div class="bg-white rounded-lg shadow-sm p-6 max-w-xl">
        <form method="POST" action="{{ route('activities.store') }}" class="space-y-4">
            @csrf
            @include('activities._form')

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-md">
                Simpan
            </button>
        </form>
    </div>
</x-layouts.app>