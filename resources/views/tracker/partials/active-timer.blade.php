<div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <p class="text-sm text-gray-600 mb-2">Current Session Duration:</p>
            <p class="text-3xl font-bold text-green-600" id="current-timer">00:00:00</p>
        </div>
        <div>
            <p class="text-sm text-gray-600 mb-2">Time Until Goal:</p>
            <p class="text-3xl font-bold text-orange-600" id="countdown-timer">
                {{ gmdate('H:i:s', $session->remaining_minutes * 60) }}
            </p>
        </div>
    </div>
</div>