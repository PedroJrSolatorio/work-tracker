@extends('layouts.app')

@section('title', 'Session Details - Work Hours Tracker')

@section('content')
    <div class="mb-6">
        <a href="{{ route('tracker.index') }}" class="text-blue-500 hover:text-blue-700 font-medium">
            ← Back to Dashboard
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <!-- Session Header -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $session->date->format('l, F j, Y') }}</h1>
                <p class="text-gray-600 mt-2">Session Details</p>
            </div>
            <span class="px-4 py-2 rounded-full text-sm font-medium
                {{ $session->status === 'active' ? 'bg-green-100 text-green-800' : 
                   ($session->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                {{ ucfirst($session->status) }}
            </span>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <x-stat-card 
                label="Target" 
                :value="number_format($session->target_minutes / 60, 1) . 'h'"
                color="gray" 
            />
            
            <x-stat-card 
                label="Worked" 
                :value="number_format($session->worked_minutes / 60, 1) . 'h'"
                color="green" 
            />
            
            {{-- USING: getRemainingMinutesAttribute() --}}
            <x-stat-card 
                label="Remaining" 
                :value="number_format($session->remaining_minutes / 60, 1) . 'h'"
                color="purple" 
            />
            
            <x-stat-card 
                label="Breaks" 
                :value="number_format($breakMinutes / 60, 1) . 'h'"
                color="orange" 
            />
        </div>

        <!-- Progress -->
        <div class="mb-8">
            <div class="flex justify-between text-sm text-gray-600 mb-2">
                <span>Overall Progress</span>
                {{-- USING: getProgressPercentageAttribute() --}}
                <span class="font-medium">{{ $session->progress_percentage }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-6">
                {{-- USING: getProgressPercentageAttribute() --}}
                <div class="bg-gradient-to-r from-blue-500 to-green-500 h-6 rounded-full transition-all duration-300 flex items-center justify-end pr-2" 
                     style="width: {{ min(100, $session->progress_percentage) }}%">
                    @if($session->progress_percentage > 10)
                        <span class="text-white text-xs font-bold">{{ $session->progress_percentage }}%</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Detailed Time Logs -->
        {{-- USING: timeLogs() relationship --}}
        @if($session->timeLogs->count() > 0)
            @include('tracker.partials.detailed-time-logs', [
                'session' => $session,
                'breakMinutes' => $breakMinutes
            ])
        @else
            <p class="text-gray-500 text-center py-8">No time logs yet for this session.</p>
        @endif
    </div>

    <div class="text-center">
        <a href="{{ route('tracker.index') }}" 
           class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium">
            Back to Dashboard
        </a>
    </div>
@endsection