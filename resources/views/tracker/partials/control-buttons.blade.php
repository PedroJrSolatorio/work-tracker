<div class="flex gap-4">
    @if($session->status !== 'active')
        <form action="{{ route('tracker.start', $session->id) }}" method="POST" class="flex-1">
            @csrf
            <button type="submit" 
                    class="w-full bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-medium text-lg">
                ▶ Start Timer
            </button>
        </form>
    @else
        <form action="{{ route('tracker.pause', $session->id) }}" method="POST" class="flex-1">
            @csrf
            <button type="submit" 
                    class="w-full bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg font-medium text-lg">
                ⏸ Pause Timer
            </button>
        </form>
    @endif

    <button onclick="document.getElementById('updateTargetModal').classList.remove('hidden')" 
            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium">
        ⚙️ Update Target
    </button>

    <form action="{{ route('tracker.reset', $session->id) }}" method="POST" 
          onsubmit="return confirm('Are you sure you want to reset this session?')">
        @csrf
        <button type="submit" 
                class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-medium">
            Reset
        </button>
    </form>
</div>