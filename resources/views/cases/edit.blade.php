<x-layouts.app title="Edit Case">
    <h1 class="text-lg font-semibold text-gray-800 mb-6">Edit Case — {{ $case->case_number }}</h1>

    <div class="bg-white rounded-lg shadow-sm p-6 max-w-2xl">
        <form method="POST" action="{{ route('cases.update', $case) }}" class="space-y-4">
            @csrf
            @method('PUT')
            @include('cases.form')

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-md">
                Update
            </button>
        </form>
    </div>

    <div class="mt-6 bg-white rounded-lg shadow-sm p-6 max-w-2xl">
        <h2 class="text-sm font-semibold text-gray-700 mb-3">Status History</h2>
        <ul class="space-y-2 text-sm">
            @forelse ($case->statusHistories as $history)
                <li class="border-l-2 border-blue-200 pl-3">
                    <span class="font-medium text-gray-800">{{ $history->status }}</span>
                    <span class="text-gray-400">— {{ $history->created_at->format('d M Y H:i') }}</span>
                    @if ($history->notes)
                        <p class="text-gray-500">{{ $history->notes }}</p>
                    @endif
                </li>
            @empty
                <li class="text-gray-400">Belum ada riwayat status.</li>
            @endforelse
        </ul>
        <p class="text-xs text-gray-400 mt-4">
            Manajemen tim/assignment lengkap akan tersedia di halaman detail case (segera menyusul).
        </p>
    </div>
</x-layouts.app>