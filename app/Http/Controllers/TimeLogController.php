<?php

namespace App\Http\Controllers;

use App\Models\TimeLog;
use Illuminate\Http\Request;

class TimeLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TimeLog $timeLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TimeLog $timeLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TimeLog $timeLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $log = TimeLog::findOrFail($id);
        $session = $log->workSession;

        //subtract the log duration from session worked minutes
        if ($log->duration_minutes) {
            $session->decrement('worked_minutes', $log->duration_minutes);
        }

        $log->delete();

        //update session status if needed
        if ($session->worked_minutes < $session->target_minutes) {
            $session->update(['status' => 'paused']);
        }

        return redirect()->back()
            ->with('success', 'Time log deleted!');
    }
}
