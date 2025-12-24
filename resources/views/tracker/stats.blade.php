<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics - Work Hours Tracker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <div class="mb-6">
            <a href="{{ route('tracker.index') }}" class="text-blue-500 hover:text-blue-700 font-medium">
                ← Back to Dashboard
            </a>
        </div>

        <h1 class="text-4xl font-bold text-gray-800 mb-8">📊 Statistics & Analytics</h1>

        <!-- Overall Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                <p class="text-sm text-gray-600 mb-2">Total Days Tracked</p>
                <p class="text-3xl font-bold text-blue-600">{{ $stats['total_days'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                <p class="text-sm text-gray-600 mb-2">Days Completed</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['completed_days'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                <p class="text-sm text-gray-600 mb-2">Total Hours</p>
                <p class="text-3xl font-bold text-purple-600">{{ $stats['total_hours_worked'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                <p class="text-sm text-gray-600 mb-2">Avg Hours/Day</p>
                <p class="text-3xl font-bold text-orange-600">{{ $stats['avg_hours_per_day'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                <p class="text-sm text-gray-600 mb-2">Completion Rate</p>
                <p class="text-3xl font-bold text-pink-600">{{ $stats['completion_rate'] }}%</p>
            </div>
        </div>

        <!-- Daily Breakdown -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-semibold mb-6">Last 30 Days Breakdown</h2>
            
            {{-- USING: Multiple accessor attributes in stats view --}}
            @if($sessions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-200">
                                <th class="text-left py-3 px-4">Date</th>
                                <th class="text-center py-3 px-4">Target</th>
                                <th class="text-center py-3 px-4">Worked</th>
                                <th class="text-center py-3 px-4">Remaining</th>
                                <th class="text-center py-3 px-4">Progress</th>
                                <th class="text-center py-3 px-4">Sessions</th>
                                <th class="text-center py-3 px-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sessions as $session)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-3 px-4">
                                        <a href="{{ route('tracker.show', $session->id) }}" 
                                           class="text-blue-500 hover:text-blue-700 font-medium">
                                            {{ $session->date->format('M j, Y') }}
                                        </a>
                                        <br>
                                        <span class="text-xs text-gray-500">{{ $session->date->format('l') }}</span>
                                    </td>
                                    <td class="text-center py-3 px-4">
                                        {{ number_format($session->target_minutes / 60, 1) }}h
                                    </td>
                                    <td class="text-center py-3 px-4 font-semibold text-blue-600">
                                        {{ number_format($session->worked_minutes / 60, 1) }}h
                                    </td>
                                    <td class="text-center py-3 px-4">
                                        {{-- USING: getRemainingMinutesAttribute() --}}
                                        <span class="{{ $session->remaining_minutes > 0 ? 'text-orange-600' : 'text-green-600' }}">
                                            {{ number_format($session->remaining_minutes / 60, 1) }}h
                                        </span>
                                    </td>
                                    <td class="text-center py-3 px-4">
                                        {{-- USING: getProgressPercentageAttribute() --}}
                                        <div class="flex items-center justify-center gap-2">
                                            <div class="w-20 bg-gray-200 rounded-full h-2">
                                                <div class="bg-blue-500 h-2 rounded-full" 
                                                     style="width: {{ min(100, $session->progress_percentage) }}%"></div>
                                            </div>
                                            <span class="text-sm font-medium">{{ $session->progress_percentage }}%</span>
                                        </div>
                                    </td>
                                    <td class="text-center py-3 px-4">
                                        {{-- USING: timeLogs() relationship --}}
                                        <span class="text-sm bg-gray-100 px-2 py-1 rounded">
                                            {{ $session->timeLogs->count() }}
                                        </span>
                                    </td>
                                    <td class="text-center py-3 px-4">
                                        <span class="text-xs px-2 py-1 rounded-full
                                            {{ $session->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                               ($session->status === 'active' ? 'bg-blue-100 text-blue-800' : 'bg-gray-200 text-gray-700') }}">
                                            {{ ucfirst($session->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No sessions tracked yet. Start working to see your stats!</p>
            @endif
        </div>

        <!-- Insights -->
        @if($sessions->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-semibold mb-4">🏆 Best Performance</h3>
                    @php
                        $bestDay = $sessions->sortByDesc('progress_percentage')->first();
                    @endphp
                    <div class="p-4 bg-green-50 rounded-lg">
                        <p class="text-sm text-gray-600">Highest Completion</p>
                        <p class="text-2xl font-bold text-green-600">{{ $bestDay->date->format('M j, Y') }}</p>
                        {{-- USING: getProgressPercentageAttribute() --}}
                        <p class="text-lg text-gray-700">{{ $bestDay->progress_percentage }}% completed</p>
                        <p class="text-sm text-gray-600 mt-2">
                            Worked {{ number_format($bestDay->worked_minutes / 60, 1) }} hours
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-semibold mb-4">💪 Consistency</h3>
                    @php
                        $recentWeek = $sessions->take(7);
                        $weeklyAvg = $recentWeek->avg('worked_minutes') / 60;
                    @endphp
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-gray-600">Last 7 Days Average</p>
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($weeklyAvg, 1) }} hours/day</p>
                        <p class="text-sm text-gray-600 mt-2">
                            {{ $recentWeek->where('status', 'completed')->count() }} out of {{ $recentWeek->count() }} days completed
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</body>
</html>