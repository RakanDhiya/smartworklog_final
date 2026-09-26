<x-layouts.guest>
    <div class="bg-white shadow-md rounded-lg p-8">
        <h1 class="text-xl font-semibold text-gray-800 mb-6 text-center">
            Smart Worklog System
        </h1>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-md">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" type="password" name="password" required
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
            </div>

            <div class="flex items-center">
                <input id="remember" type="checkbox" name="remember"
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-md transition-colors">
                Login
            </button>
        </form>
    </div>
</x-layouts.guest>