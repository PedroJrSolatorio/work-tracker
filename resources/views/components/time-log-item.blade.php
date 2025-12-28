@props(['log', 'index', 'nextLog' => null])

<div class="flex justify-between items-center p-3 bg-gray-50 rounded hover:bg-gray-100">
    <div class="flex-1">
        <div class="flex items-center gap-2">
            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded font-medium">
                Session {{ $index + 1 }}
            </span>
            <span class="font-medium">{{ $log->start_time->format('h:i A') }}</span>
            @if($log->end_time)
                <span class="text-gray-600"> → {{ $log->end_time->format('h:i A') }}</span>
            @else
                <span class="text-green-600 font-medium"> → Active Now</span>
            @endif
        </div>
        @if($log->end_time && $nextLog)
            @php
                $breakMinutes = $log->end_time->diffInMinutes($nextLog->start_time);
            @endphp
            @if($breakMinutes > 0)
                <p class="text-xs text-gray-500 mt-1">
                    ☕ Break: {{ number_format($breakMinutes, 1) }} min before next session
                </p>
            @endif
        @endif
    </div>
    <div class="flex items-center gap-3">
        @if($log->duration_minutes)
            <div class="text-right">
                <p class="text-lg font-bold text-blue-600">
                    {{ gmdate('H:i:s', $log->duration_minutes * 60) }}
                </p>
                <p class="text-xs text-gray-500">
                    {{ $log->duration_minutes }} minutes
                </p>
            </div>
        @else
            <div class="text-right">
                <p class="text-sm text-green-600 font-medium animate-pulse">
                    In Progress...
                </p>
            </div>
        @endif
        @if($log->end_time)
            <form action="{{ route('timelog.destroy', $log->id) }}" method="POST" 
                  onsubmit="return confirm('Delete this time log?')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-700 text-sm">
                    🗑️
                </button>
            </form>
        @endif
    </div>
</div>