<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 lg:px-6">
    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <div class="hidden lg:block"></div>

    <div class="flex items-center gap-4" x-data="{ open: false }">
        <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 text-sm text-gray-700">
            <span class="font-medium">{{ auth()->user()->name }}</span>
            <span class="text-xs bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full">
                {{ auth()->user()->getRoleNames()->first() }}
            </span>
        </button>

        <div x-show="open" x-transition @click.outside="open = false"
            class="absolute right-4 top-14 lg:right-6 bg-white border border-gray-200 rounded-md shadow-lg w-48 py-1 z-40">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Logout
                </button>
            </form>
        </div>
    </div>
</header>