<x-modal id="updateTargetModal" title="Update Target Hours">
    <form action="{{ route('tracker.update', $session->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Current Target: <span class="text-blue-600 font-bold">{{ number_format($session->target_minutes / 60, 1) }} hours</span>
            </label>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Already Worked: <span class="text-green-600 font-bold">{{ number_format($session->worked_minutes / 60, 1) }} hours</span>
            </label>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">New Target Hours</label>
            <input type="number" name="target_hours" step="0.5" min="0.5" max="24" 
                   value="{{ number_format($session->target_minutes / 60, 1) }}" required
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <p class="text-xs text-gray-500 mt-1">Enter the new target duration for today</p>
        </div>

        <div class="flex gap-3">
            <button type="button" 
                    onclick="document.getElementById('updateTargetModal').classList.add('hidden')"
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-medium">
                Cancel
            </button>
            <button type="submit" 
                    class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium">
                Update Target
            </button>
        </div>
    </form>
</x-modal>