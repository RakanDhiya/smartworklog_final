<aside
    x-show="sidebarOpen || window.innerWidth >= 1024"
    x-transition
    class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 overflow-y-auto lg:static lg:translate-x-0"
>
    <div class="h-16 flex items-center px-6 border-b border-gray-200">
        <span class="font-semibold text-gray-800">Smart Worklog</span>
    </div>

    <nav class="p-4 space-y-6">
        <div>
            <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-nav-link>
        </div>

        @if (auth()->user()->can('clients.view') || auth()->user()->can('cases.view'))
            <div>
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase mb-1">Case Management</p>
                <x-nav-link href="{{ route('cases.index') }}" :active="request()->routeIs('cases.*')">Cases</x-nav-link>
                <x-nav-link href="{{ route('clients.index') }}" :active="request()->routeIs('clients.*')">Clients</x-nav-link>
            </div>
        @endif

        @if (auth()->user()->can('activities.view') || auth()->user()->can('tasks.view') || auth()->user()->can('schedules.view'))
            <div>
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase mb-1">Work Management</p>
                <x-nav-link :disabled="true">Activities</x-nav-link>
                <x-nav-link :disabled="true">Tasks</x-nav-link>
                <x-nav-link :disabled="true">Schedules</x-nav-link>
            </div>
        @endif

        @if (auth()->user()->can('attendances.view'))
            <div>
                <x-nav-link :disabled="true">Attendance</x-nav-link>
            </div>
        @endif

        @if (auth()->user()->can('documents.view'))
            <div>
                <x-nav-link :disabled="true">Documents</x-nav-link>
            </div>
        @endif

        @if (auth()->user()->can('reports.view'))
            <div>
                <x-nav-link :disabled="true">Reports</x-nav-link>
            </div>
        @endif

        @if (auth()->user()->can('employees.view'))
            <div>
                <x-nav-link :disabled="true">Employees</x-nav-link>
            </div>
        @endif

        <div>
            <x-nav-link :disabled="true">Notifications</x-nav-link>
        </div>

        @if (auth()->user()->can('audit_logs.view') || auth()->user()->can('office_profile.manage'))
            <div>
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase mb-1">Settings</p>
                @can('audit_logs.view')
                    <x-nav-link :disabled="true">Audit Logs</x-nav-link>
                @endcan
                @can('office_profile.manage')
                    <x-nav-link :disabled="true">Office Profile</x-nav-link>
                @endcan
            </div>
        @endif
    </nav>
</aside>

<div
    x-show="sidebarOpen"
    x-transition.opacity
    @click="sidebarOpen = false"
    class="fixed inset-0 bg-black/30 z-20 lg:hidden"
></div>