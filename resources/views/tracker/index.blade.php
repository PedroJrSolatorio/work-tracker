@extends('layouts.app')  

@section('title', 'Dashboard - Work Hours Tracker')

@section('content')
@if (!$session)
    {{-- Create New Session --}}
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-semibold mb-4">Set Today's Target</h2>
            <form action="{{ route('tracker.store') }}" method="POST" class="flex gap-4">
                @csrf
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Target Hours</label>
                    <input type="number" name="target_hours" step="0.5" min="0.5" max="24" 
                           value="8" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-end">
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-medium">
                        Create Session
                    </button>
                </div>
            </form>
        </div>
@else
    {{-- Active Session --}}
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">Today's Session</h2>
                        <p class="text-gray-600">{{now()->format('l, F j, Y')}}</p>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-medium {{ $session->status === 'active' ? 'bg-green-100 text-green-800' : ($session->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ ucfirst($session->status) }}
                    </span>
                </div>

                {{-- Progress Bar Component --}}
                {{-- USING: getProgressPercentageAttribute() --}}
                <x-progress-bar :percentage="$session->progress_percentage" />

            {{-- Time Stats using Components --}}
           <div class="grid grid-cols-3 gap-4 mb-6">
                <x-stat-card 
                    label="Target" 
                    :value="number_format($session->target_minutes / 60, 1) . 'h'"
                    :detail="gmdate('H:i:s', $session->target_minutes * 60)"
                    color="gray" 
                />

                 <x-stat-card 
                    label="Worked" 
                    :value="number_format($session->worked_minutes / 60, 1) . 'h'"
                    :detail="gmdate('H:i:s', $session->worked_minutes * 60)"
                    color="blue"
                    id="worked-time-card"
                />
                
                <x-stat-card 
                    label="Remaining" 
                    :value="number_format($session->remaining_minutes / 60, 1) . 'h'"
                    :detail="gmdate('H:i:s', $session->remaining_minutes * 60)"
                    color="green"
                    id="remaining-time-card"
                />
            </div>

            {{-- Active Timer Display --}}
            @if ($session->status === 'active')
                @include('tracker.partials.active-timer')
            @endif

            {{-- Control Buttons --}}
            @include('tracker.partials.control-buttons', ['session' => $session])

            {{-- Update Target Modal --}}
            @include('tracker.partials.update-target-modal', ['session' => $session])
        </div>

        {{-- Time Logs Section --}}
        {{-- USING: timeLogs() relationship --}}
        @if ($session->timeLogs->count() > 0)
            @include('tracker.partials.time-logs', ['session' => $session])
        @endif
@endif

        {{-- Recent Sessions --}}
        @if ($recentSessions->count() > 0)
            @include('tracker.partials.recent-sessions', ['recentSessions' => $recentSessions])
        @endif
@endsection

@if ($session && $session->status === 'active')
    @push('scripts')
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
            const remainingSeconds = remainingMinutes * 60;

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
    @endpush
@endif