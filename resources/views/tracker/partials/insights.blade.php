<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Best Performance -->
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

    <!-- Consistency -->
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