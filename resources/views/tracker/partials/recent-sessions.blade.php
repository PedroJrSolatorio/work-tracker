<div class="bg-white rounded-lg shadow-lg p-6 mt-8">
    <h3 class="text-xl font-semibold mb-4">Recent Sessions</h3>
    <div class="space-y-3">
        @foreach($recentSessions as $recent)
            <a href="{{ route('tracker.show', $recent->id) }}" 
               class="flex justify-between items-center p-3 bg-gray-50 rounded hover:bg-gray-100 transition">
                <div>
                    <p class="font-medium">{{ $recent->date->format('M j, Y') }}</p>
                    <p class="text-sm text-gray-600">
                        {{ number_format($recent->worked_minutes / 60, 1) }}h / 
                        {{ number_format($recent->target_minutes / 60, 1) }}h
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-700">{{ $recent->progress_percentage }}%</p>
                    <span class="text-xs px-2 py-1 rounded-full
                        {{ $recent->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-gray-200 text-gray-700' }}">
                        {{ ucfirst($recent->status) }}
                    </span>
                </div>
            </a>
        @endforeach
    </div>
</div>