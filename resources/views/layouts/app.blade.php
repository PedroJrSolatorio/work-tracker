<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Work Hours Tracker')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-100">
    {{-- Navigation --}}
    <nav class="bg-white shadow-md mb-8">
       <div class="container mx-auto px-4 py-4">
         <div class="flex justify-between items-center">
            <a href="{{ route('tracker.index') }}" class="text-2xl font-bold text-gray-800">
                ⏱️ Work Hours Tracker
            </a>
            <div class="flex gap-4">
                <a href="{{ route('tracker.index') }}" class="px-4 py-2 rounded-lg {{ request()->routeIs('tracker.index') ? 'bg-blue-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    Dashboard
                </a>
                <a href="{{ route('tracker.stats') }}" class="px-4 py-2 rounded-lg {{ request()->routeIs('tracker.stats') ? 'bg-blue-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    Statistics
                </a>
            </div>
        </div>
       </div>
    </nav>

    {{-- Main Content --}}
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        {{-- flash messages --}}
        @if (session('success'))
            <x-alert type="success" :message="session('success')" />
        @endif

        @if (session('error'))
            <x-alert type="error" :message="session('error')" />
        @endif

        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>