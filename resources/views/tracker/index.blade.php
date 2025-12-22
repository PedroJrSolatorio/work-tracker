<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Hours Tracker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <h1 class="text-4xl font-bold text-gray-800 mb-8">Work Hours Tracker</h1>
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (!$session)
            {{-- Create New Session --}}
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <h2 class="text-2xl font-semibold mb-4">Set Today's Target</h2>
                <form action="{{ route('tracker.store') }}" method="POST" class="flex gap-4">
                    @csrf
                    <div class="flex-1">
                        <label for="" class="block text-sm font-medium text-gray-700 mb-2">Target Hours</label>
                        <input type="number" name="target_hours" step="0.5" min="0.50" max="24" value="8" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-medium">
                            Create Session
                        </button>
                    </div>
                </form>
            </div>
        @else
            {{-- Active Sessions --}}
            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">Today's Session</h2>
                        <p>{{now()->format('l, F j, Y')}}</p>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-medium {{ $session->status === 'active' ? 'bg-green-100 text-green-800' : ($session->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ ucfirst($session->status) }}
                    </span>
                </div>

                {{-- Progress Bar --}}
                <div class="mb-6">
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>Progress</span>
                        {{-- USING: getProgressPercentageAttribute() --}}
                        <span>{{$session->progress_percentage}}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        {{-- USING: getProgressPercentageAttribute() --}}
                        <div class="bg-blue-500 h-4 rounded-full transition-all duration-300" style="width: {{ min(100, $session->progress_percentage) }}%"></div>
                    </div>
                </div>

                {{-- Time stats --}}
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Target</p>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ number_format($session->target_minutes / 60, 1) }}h
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ gmdate('H:i:s', $session->target_minutes * 60) }}
                        </p>
                    </div>
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <p  class="text-sm text-gray-600 mb-1">Worked</p>
                        <p class="text-2xl font-bold text-blue-600" id="worked-time">
                            {{ number_format($session->worked_minutes / 60, 1) }}h
                        </p>
                        <p class="text-xs text-gray-500 mt-1" id="worked-time-detailed">
                            {{ gmdate('H:i:s', $session->worked_minutes * 60) }}
                        </p>
                    </div>
                    <div class="text-center p-4 bg-green-500 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Remaining</p>
                        {{-- USING: getRemainingMinutesAttribute() --}}
                        <p class="text-2xl font-bold text-green-600" id="remaining-time">
                            {{ number_format($session->remaining_minutes / 60, 1) }}h
                        </p>
                        <p class="text-xs text-gray-500 mt-1" id="remaining-time-detailed">
                            {{ gmdate('H:i:s', $session->remaining_minutes * 60) }}
                        </p>
                    </div>
                </div>

                {{-- Current Timer (if active) --}}
                @if ($session->status === 'active')
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-2">Current Session Duration:</p>
                                <p class="text-3xl font-bold text-green-600" id="current-timer">
                                    00:00:00
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-2">Time Until Goal:</p>
                                <p class="text-3xl font-bold text-orange-600" id="countdown-timer">
                                     {{ gmdate('H:i:s', $session->remaining_minutes * 60) }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Control Buttons --}}
                <div class="flex gap-4">
                    @if ($session->status !== 'active')
                        <form action="{{ route('tracker.start', $session->id) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-medium text-lg">
                                ▶ Start Timer
                            </button>
                        </form>
                        @else
                        <form action="{{ route('tracker.pause', $session->id) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg font-medium text-lg">
                                ⏸ Pause Timer
                            </button>
                        </form>
                    @endif


                </div>
            </div>
        @endif
    </div>

    @if ($session && $session->status === 'active')
        <script>
            //get initial value from server
            const startTime = {{ $session->current_start_time->timestamp * 1000 }};
            const initialWorkedMinutes = {{ $session->worked_minutes }};
            const targetMinutes = {{ $session->target_minutes }};

            function formatTime(seconds){
                const hours = Math.floor(seconds / 3600 );
                const minutes = Math.floor((seconds % 3600) / 60);
                const secs = seconds % 60;
                return String(hours).padStart(2, '0') + ':' + 
                   String(minutes).padStart(2, '0') + ':' + 
                   String(secs).padStart(2, '0');
            }

            function updateTimer(){
                const now = new Date().getTime();
                const elapsedSeconds = Math.floor((now - startTime) / 1000);
                const elapsedMinutes = Math.floor(elapsedSeconds / 60);

                //update current session timer (count up from 00:00:00)
                document.getElementById('current-timer').textContent = formatTime(elapsedSeconds);

                //calculate total worked time
                const totalWorkedMinutes = initialWorkedMinutes + elapsedMinutes;
                const totalWorkedSeconds = totalWorkedMinutes * 60;

                //update worked time display
                document.getElementById('worked-time').textContent = (totalWorkedMinutes / 60).toFixed(1) + 'h';
                document.getElementById('worked-time-detailed').textContent = formatTime(totalWorkedSeconds);

                //calculate remaining time
                const remainingMinutes = Math.max(0, targetMinutes - totalWorkedMinutes);
                const remainingSeconds = remaningMinutes * 60;

                //update remaining time display
                document.getElementById('remaining-time').textContent = (remainingMinutes / 60).toFixed(1) + 'h';
                document.getElementById('remaining-time-detailed').textContent = formatTime(remainingSeconds);

                //update countdown timer (time until goal)
                document.getElementById('countdown-timer').textContent = formatTime(remainingSeconds);

                //update progress bar
                const progressPercentage = Math.min(100, (totalWorkedMinutes / targetMinutes) * 100);
                const progressBar = document.querySelector('.bg-blue-500');
                if(progressBar){
                    progressBar.style.width = progressPercentage.toFixed(2) + '%';
                }

                 //update progress percentage text
                const progressText = document.querySelector('.bg-blue-500').parentElement.previousElementSibling.querySelector('span:last-child');
                if (progressText) {
                    progressText.textContent = progressPercentage.toFixed(0) + '%';
                }

                //change countdown color when close to completion
                const countdownElement = document.getElementById('countdown-timer');
                if (remainingMinutes <= 30) {
                    countdownElement.classList.remove('text-orange-600');
                    countdownElement.classList.add('text-red-600');
                }
                
                //celebrate when goal is reached
                if (remainingMinutes === 0 && !window.goalReached) {
                    window.goalReached = true;
                    countdownElement.textContent = '🎉 GOAL REACHED!';
                    countdownElement.classList.add('animate-pulse');
                }
            }

            //update every second
            setInterval(updateTimer, 1000);

            //run immediately
            updateTimer();
        </script>
    @endif
</body>
</html>