<div class="bg-white rounded-lg shadow-lg p-6">
    <h3 class="text-xl font-semibold mb-4">Time Logs ({{ $session->timeLogs->count() }} sessions)</h3>
    <div class="space-y-2">
        {{-- USING: timeLogs() relationship to loop through logs --}}
        @foreach($session->timeLogs as $index => $log)
            <x-time-log-item 
                :log="$log" 
                :index="$index"
                :nextLog="$session->timeLogs[$index + 1] ?? null"
            />
        @endforeach
    </div>
    
    <!-- Summary -->
    <div class="mt-4 pt-4 border-t border-gray-200">
        <div class="grid grid-cols-3 gap-4 text-center">
            <div>
                <p class="text-xs text-gray-600">Total Sessions</p>
                <p class="text-lg font-bold text-gray-800">{{ $session->timeLogs->count() }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-600">Total Time Worked</p>
                <p class="text-lg font-bold text-blue-600">
                    {{ gmdate('H:i:s', $session->worked_minutes * 60) }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-600">Avg Session Length</p>
                <p class="text-lg font-bold text-purple-600">
                    @php
                        $completedLogs = $session->timeLogs->where('duration_minutes', '>', 0);
                        $avgDuration = $completedLogs->count() > 0 
                            ? $completedLogs->avg('duration_minutes') 
                            : 0;
                    @endphp
                    {{ number_format($avgDuration, 0) }} min
                </p>
            </div>
        </div>
    </div>
</div>