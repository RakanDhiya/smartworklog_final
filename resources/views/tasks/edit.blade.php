<x-layouts.app title="Edit Task">
    <h1 class="text-lg font-semibold text-gray-800 mb-6">Edit Task</h1>

    <div class="bg-white rounded-lg shadow-sm p-6 max-w-2xl">
        <form method="POST" action="{{ route('tasks.update', $task) }}" class="space-y-4">
            @csrf
            @method('PUT')
            @include('tasks._form')

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-md">
                Update
            </button>
        </form>
    </div>
</x-layouts.app>