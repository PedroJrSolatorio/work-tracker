<div>
    <h2 class="text-xl font-semibold mb-4">Detailed Time Logs ({{ $session->timeLogs->count() }} sessions)</h2>
    
    <div class="space-y-3">
        @foreach($session->timeLogs as $index => $log)
            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                <!-- Session Header -->
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

                <!-- Break Duration -->
                @if($index < $session->timeLogs->count() - 1 && $log->end_time)
                    @php
                        $nextLog = $session->timeLogs[$index + 1];
                        $breakDuration = $log->end_time->diffInMinutes($nextLog->start_time);
                    @endphp
                    @if($breakDuration > 0)
                        <div class="mt-2 pt-2 border-t border-gray-200">
                            <p class="text-xs text-gray-500">
                                ☕ Break: {{ number_format($breakDuration, 1) }} minutes 
                                ({{ $log->end_time->format('g:i A') }} - {{ $nextLog->start_time->format('g:i A') }})
                            </p>
                        </div>
                    @endif
                @endif

                <!-- Parent Session Info -->
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
</div>