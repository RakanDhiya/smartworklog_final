<x-layouts.app title="Edit Attendance">
    <h1 class="text-lg font-semibold text-gray-800 mb-6">
        Edit Attendance — {{ $attendance->user->name }} ({{ $attendance->date->format('d M Y') }})
    </h1>

    <div class="bg-white rounded-lg shadow-sm p-6 max-w-lg">
        <form method="POST" action="{{ route('attendances.update', $attendance) }}" class="space-y-4">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="p-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-md">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
                    @foreach (['Present', 'Late', 'Leave', 'Sick', 'Absent'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $attendance->status) === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Check In</label>
                    <input type="datetime-local" name="check_in"
                        value="{{ old('check_in', $attendance->check_in?->format('Y-m-d\TH:i')) }}"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Check Out</label>
                    <input type="datetime-local" name="check_out"
                        value="{{ old('check_out', $attendance->check_out?->format('Y-m-d\TH:i')) }}"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Catatan</label>
                <textarea name="notes" rows="2"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">{{ old('notes', $attendance->notes) }}</textarea>
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-md">
                Update
            </button>
        </form>
    </div>
</x-layouts.app>