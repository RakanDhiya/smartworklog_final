<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Smart Worklog System' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">
        <x-sidebar />

        <div class="flex-1 flex flex-col overflow-hidden">
            <x-topbar />

            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>