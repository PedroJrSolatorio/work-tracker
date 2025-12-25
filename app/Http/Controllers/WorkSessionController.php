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

    public function reset($id)
    {
        $session = WorkSession::findOrFail($id);

        $session->timeLogs()->delete();
        $session->update([
            'worked_minutes' => 0,
            'status' => 'paused',
            'current_start_time' => null
        ]);

        return redirect()->route('tracker.index')
            ->with('success', 'Session reset!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $session = WorkSession::with('timeLogs')->findOrFail($id);

        // Calculate total break time
        $breakMinutes = 0;
        $logs = $session->timeLogs;

        for ($i = 0; $i < $logs->count() - 1; $i++) {
            if ($logs[$i]->end_time && $logs[$i + 1]->start_time) {
                $breakMinutes += $logs[$i]->end_time->diffInMinutes($logs[$i + 1]->start_time);
            }
        }

        return view('tracker.show', compact('session', 'breakMinutes'));
    }

    public function stats()
    {
        $sessions = WorkSession::orderBy('date', 'desc')->take(30)->get();

        $stats = [
            'total_days' => $sessions->count(),
            'completed_days' => $sessions->where('status', 'completed')->count(),
            'total_hours_worked' => round($sessions->sum('worked_minutes') / 60, 1),
            'avg_hours_per_day' => $sessions->count() > 0
                ? round($sessions->avg('worked_minutes') / 60, 1)
                : 0,
            'completion_rate' => $sessions->count() > 0
                ? round(($sessions->where('status', 'completed')->count() / $sessions->count()) * 100, 1)
                : 0,
        ];

        return view('tracker.stats', compact('sessions', 'stats'));
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
    public function update(Request $request, $id)
    {
        $request->validate([
            'target_hours' => 'required|decimal:0,1|min:0.5|max:24'
        ]);

        $session = WorkSession::findOrFail($id);
        $newTargetMinutes = $request->target_hours * 60;

        //warn if new target is less than already worked time
        if ($newTargetMinutes < $session->worked_minutes) {
            return redirect()->route('tracker.index')
                ->with('success', 'Target updated! Note: You\'ve already exceeded this target with' . number_format($session->worked_minutes / 60, 1) . ' hours worked.');
        }

        $session->update([
            'target_minutes' => $newTargetMinutes
        ]);

        //update status if applicable
        if ($session->worked_minutes >= $newTargetMinutes) {
            $session->update(['status' => 'completed']);
        } elseif ($session->status === 'completed') {
            $session->update(['status' => 'paused']);
        }

        return redirect()->route('tracker.index')
            ->with('success', 'Target updated to ' . $request->target_hours . ' hours!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkSession $workSession)
    {
        //
    }
}
