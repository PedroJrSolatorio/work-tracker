<?php

namespace App\Http\Controllers;

use App\Models\TimeLog;
use App\Models\WorkSession;
use Illuminate\Http\Request;

class WorkSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today = now()->toDateString();
        $session = WorkSession::whereDate('date', $today)->first();

        $recentSessions = WorkSession::orderBy('date', 'desc')
            ->take(7)
            ->get();

        return view('tracker.index', compact('session', 'recentSessions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'target_hours' => 'required|decimal:0,1|min:0.5|max:24'
        ]);

        $today = now()->toDateString();

        $session = WorkSession::updateOrCreate(
            ['date' => $today],
            [
                'target_minutes' => $request->target_hours * 60,
                'worked_minutes' => 0,
                'status' => 'paused'
            ]
        );

        return redirect()->route('tracker.index')
            ->with('success', 'Work session created! Target: ' . $request->target_hours . ' hours');
    }

    public function start($id)
    {
        $session = WorkSession::findOrFail($id);

        $session->update([
            'status' => 'active',
            'current_start_time' => now()
        ]);

        TimeLog::create([
            'work_session_id' => $session->id,
            'start_time' => now()
        ]);

        return redirect()->route('tracker.index')
            ->with('success', 'Timer started!');
    }

    public function pause($id)
    {
        $session = WorkSession::findOrFail($id);

        if ($session->status === 'active') {
            $currentLog = $session->timeLogs()
                ->whereNull('end_time')
                ->latest()
                ->first();

            if ($currentLog) {
                //use absolute value to ensure positive duration
                $duration = abs($currentLog->start_time->diffInMinutes(now()));

                $currentLog->update([
                    'end_time' => now(),
                    'duration_minutes' => $duration
                ]);

                $session->increment('worked_minutes', $duration);
            }

            $session->update([
                'status' => 'paused',
                'current_start_time' => null
            ]);

            //check if completed
            if ($session->worked_minutes >= $session->target_minutes) {
                $session->update(['status' => 'completed']);
            }
        }

        return redirect()->route('tracker.index')
            ->with('success', 'Timer paused!');
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkSession $workSession)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkSession $workSession)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorkSession $workSession)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkSession $workSession)
    {
        //
    }
}
