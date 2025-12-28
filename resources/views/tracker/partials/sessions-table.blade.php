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