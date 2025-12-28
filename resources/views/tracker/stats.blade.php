@extends('layouts.app')

@section('title', 'Statistics - Work Hours Tracker')

@section('content')
    <h1 class="text-4xl font-bold text-gray-800 mb-8">📊 Statistics & Analytics</h1>

    <!-- Overall Stats -->
    @include('tracker.partials.overall-stats', ['stats' => $stats])

    <!-- Daily Breakdown -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h2 class="text-2xl font-semibold mb-6">Last 30 Days Breakdown</h2>
        
        {{-- USING: Multiple accessor attributes in stats view --}}
        @if($sessions->count() > 0)
            @include('tracker.partials.sessions-table', ['sessions' => $sessions])
        @else
            <p class="text-gray-500 text-center py-8">No sessions tracked yet. Start working to see your stats!</p>
        @endif
    </div>

    <!-- Insights -->
    @if($sessions->count() > 0)
        @include('tracker.partials.insights', ['sessions' => $sessions])
    @endif
@endsection