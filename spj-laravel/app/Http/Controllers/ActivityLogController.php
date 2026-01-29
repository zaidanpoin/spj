<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activity logs.
     */
    public function index(Request $request)
    {
        $query = Activity::with(['causer', 'subject'])
            ->latest();

        // Filter by date range
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter by event type
        if ($request->has('event') && $request->event != '') {
            $query->where('event', $request->event);
        }

        // Filter by causer (user)
        if ($request->has('user_id') && $request->user_id != '') {
            $query->where('causer_id', $request->user_id);
        }

        // Filter by subject type
        if ($request->has('subject_type') && $request->subject_type != '') {
            $query->where('subject_type', $request->subject_type);
        }

        $activities = $query->paginate(20);
        $users = \App\Models\User::orderBy('name')->get();
        $events = Activity::distinct()->pluck('event');
        $subjectTypes = Activity::distinct()->pluck('subject_type');

        return view('activity-logs.index', compact('activities', 'users', 'events', 'subjectTypes'));
    }

    /**
     * Display the specified activity log.
     */
    public function show(string $id)
    {
        $activity = Activity::with(['causer', 'subject'])->findOrFail($id);
        return view('activity-logs.show', compact('activity'));
    }
}
