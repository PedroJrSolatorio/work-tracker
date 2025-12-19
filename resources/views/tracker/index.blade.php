<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Hours Tracker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <h1 class="text-4xl font-bold text-gray-800 mb-8">Work Hours Tracker</h1>
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (!$session)
            {{-- Create New Session --}}
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <h2 class="text-2xl font-semibold mb-4">Set Today's Target</h2>
                <form action="{{ route('tracker.store') }}" method="POST" class="flex gap-4">
                    @csrf
                    <div class="flex-1">
                        <label for="" class="block text-sm font-medium text-gray-700 mb-2">Target Hours</label>
                        <input type="number" name="target_hours" step="0.5" min="0.50" max="24" value="8" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-medium">
                            Create Session
                        </button>
                    </div>
                </form>
            </div>
        @else
            {{-- Active Sessions --}}
            <div class="bg-dark">
                <p class="bg-blue-500">hey</p>
            </div>
        @endif
    </div>
</body>
</html>