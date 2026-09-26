<x-layouts.app title="Dashboard Staff">
    <div class="mb-6">
        <h1 class="text-lg font-semibold text-gray-800">
            Selamat datang, {{ auth()->user()->name }}
        </h1>
        <p class="text-sm text-gray-500">Dashboard Staff — ringkasan tugas Anda</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <x-stat-card label="Assigned Cases" :value="$stats['assigned_cases']" />
        <x-stat-card label="My Activities" :value="$stats['my_activities']" />
        <x-stat-card label="My Attendance" :value="$stats['my_attendance']" />
        <x-stat-card label="My Tasks" :value="$stats['my_tasks']" />
        <x-stat-card label="Upcoming Schedule" :value="$stats['upcoming_schedule']" />
    </div>
</x-layouts.app>