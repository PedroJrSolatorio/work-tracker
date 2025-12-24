<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Details - Work Hours Tracker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="mb-6">
            <a href="{{ route('tracker.index') }}" class="text-blue-500 hover:text-blue-700 font-medium">
                ← Back to Dashboard
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $session->date->format('l, F j, Y') }}</h1>
                    <p class="text-gray-600 mt-2">Session Details</p>
                </div>
                <span class="px-4 py-2 rounded-full text-sm font-medium
                    {{ $session->status === 'active' ? 'bg-green-100 text-green-800' : 
                       ($session->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                    {{ ucfirst($session->status) }}
                </span>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Target</p>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ number_format($session->target_minutes / 60, 1) }}h
                    </p>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Worked</p>
                    <p class="text-2xl font-bold text-green-600">
                        {{ number_format($session->worked_minutes / 60, 1) }}h
                    </p>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Remaining</p>
                    {{-- USING: getRemainingMinutesAttribute() --}}
                    <p class="text-2xl font-bold text-purple-600">
                        {{ number_format($session->remaining_minutes / 60, 1) }}h
                    </p>
                </div>
                <div class="text-center p-4 bg-orange-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Breaks</p>
                    <p class="text-2xl font-bold text-orange-600">
                        {{ number_format($breakMinutes / 60, 1) }}h
                    </p>
                </div>
            </div>

            <!-- Progress -->
            <div class="mb-8">
                <div class="flex justify-between text-sm text-gray-600 mb-2">
                    <span>Overall Progress</span>
                    {{-- USING: getProgressPercentageAttribute() --}}
                    <span class="font-medium">{{ $session->progress_percentage }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-6">
                    {{-- USING: getProgressPercentageAttribute() --}}
                    <div class="bg-gradient-to-r from-blue-500 to-green-500 h-6 rounded-full transition-all duration-300 flex items-center justify-end pr-2" 
                         style="width: {{ min(100, $session->progress_percentage) }}%">
                        @if($session->progress_percentage > 10)
                            <span class="text-white text-xs font-bold">{{ $session->progress_percentage }}%</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Detailed Time Logs -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Detailed Time Logs ({{ $session->timeLogs->count() }} sessions)</h2>
                
                {{-- USING: timeLogs() relationship --}}
                @if($session->timeLogs->count() > 0)
                    <div class="space-y-3">
                        @foreach($session->timeLogs as $index => $log)
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <p class="font-semibold text-gray-800">Session {{ $index + 1 }}</p>
                                        <p class="text-sm text-gray-600">
                                            {{ $log->start_time->format('g:i A') }}
                                            @if($log->end_time)
                                                - {{ $log->end_time->format('g:i A') }}
                                            @else
                                                - <span class="text-green-600 font-medium">Active Now</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        @if($log->duration_minutes)
                                            <p class="text-lg font-bold text-blue-600">
                                                {{ gmdate('H:i:s', $log->duration_minutes * 60) }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $log->duration_minutes }} minutes
                                            </p>
                                        @else
                                            <p class="text-sm text-green-600 font-medium">In Progress...</p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Show break duration between sessions --}}
                                @if($index < $session->timeLogs->count() - 1 && $log->end_time)
                                    @php
                                        $nextLog = $session->timeLogs[$index + 1];
                                        $breakDuration = $log->end_time->diffInMinutes($nextLog->start_time);
                                    @endphp
                                    @if($breakDuration > 0)
                                        <div class="mt-2 pt-2 border-t border-gray-200">
                                            <p class="text-xs text-gray-500">
                                                ☕ Break: {{ $breakDuration }} minutes 
                                                ({{ $log->end_time->format('g:i A') }} - {{ $nextLog->start_time->format('g:i A') }})
                                            </p>
                                        </div>
                                    @endif
                                @endif

                                {{-- USING: workSession() relationship (reverse) --}}
                                <div class="mt-2 pt-2 border-t border-gray-200">
                                    <p class="text-xs text-gray-500">
                                        Part of session: {{ $log->workSession->date->format('M j, Y') }} 
                                        ({{ $log->workSession->status }})
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Summary Stats -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="font-semibold text-gray-800 mb-2">Session Summary</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-600">Total Work Sessions:</p>
                                <p class="font-semibold">{{ $session->timeLogs->count() }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Average Session Length:</p>
                                <p class="font-semibold">
                                    {{ $session->timeLogs->count() > 0 
                                        ? number_format($session->worked_minutes / $session->timeLogs->count(), 0) 
                                        : 0 }} minutes
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-600">Total Break Time:</p>
                                <p class="font-semibold">{{ number_format($breakMinutes / 60, 1) }} hours</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Longest Session:</p>
                                <p class="font-semibold">
                                    {{ $session->timeLogs->max('duration_minutes') ?? 0 }} minutes
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No time logs yet for this session.</p>
                @endif
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('tracker.index') }}" 
               class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium">
                Back to Dashboard
            </a>
        </div>
    </div>
</body>
</html>