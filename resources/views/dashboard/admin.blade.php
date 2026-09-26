<x-layouts.app title="Dashboard Admin">
    <div class="mb-6">
        <h1 class="text-lg font-semibold text-gray-800">
            Selamat datang, {{ auth()->user()->name }}
        </h1>
        <p class="text-sm text-gray-500">Dashboard Admin — ringkasan operasional kantor</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <x-stat-card label="Total Employees" :value="$stats['total_employees']" />
        <x-stat-card label="Active Cases" :value="$stats['active_cases']" />
        <x-stat-card label="Completed Cases" :value="$stats['completed_cases']" />
        <x-stat-card label="Today's Attendance" :value="$stats['today_attendance']" />
        <x-stat-card label="Pending Tasks" :value="$stats['pending_tasks']" />
        <x-stat-card label="Upcoming Schedules" :value="$stats['upcoming_schedules']" />
    </div>

    <div class="mt-6 bg-blue-50 border border-blue-100 text-blue-700 text-sm rounded-md p-4">
        Statistik case/attendance/task masih placeholder — akan aktif otomatis begitu modul terkait dibangun (Phase 4–8), tanpa perlu ubah halaman ini.
    </div>
</x-layouts.app>