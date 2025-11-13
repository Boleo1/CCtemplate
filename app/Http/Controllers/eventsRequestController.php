<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\centerRequests;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Events;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class eventsRequestController extends Controller
{
    public function index()
    {
        $requests = centerRequests::with('reviewer')->orderBy('created_at', 'desc')->paginate(10);
        return view('dashboard.requests.index', compact('requests'));
    }
  public function submit(Request $request)
    {

        $validated = $request->validate([
            'eventType' => 'required|string',
            'date' => 'required|date|after_or_equal:today',
            'endDate' => 'nullable|date|after_or_equal:date',
            'eventTime' => 'required',
            'endTime' => 'nullable|string',
            'allDay' => 'nullable|boolean',
            'requesterEmail' => 'required|email',
            'eventDescription' => 'required|string|max:1000',
        ]);

        $type        = $validated['eventType'];
        $startDate   = Carbon::parse($validated['date']);
        $endDate     = isset($validated['endDate'])
                         ? Carbon::parse($validated['endDate'])
                         : null;
        $allDay      = $request->boolean('allDay');
        $startTime   = $validated['eventTime'] ?: null;
        $endTime     = $validated['endTime']   ?: null;

        // --- Special rules for Wakes ---
        if ($type === 'Wake') {
            $allDay = true;

            // default to 2-day slot if no end date
            if (!$endDate) {
                $endDate = $startDate->copy()->addDays(2);
            }

            // times not needed for wakes
            $startTime = null;
            $endTime   = null;
        } else {
            // Non-wake defaults

            // If no end date, same day
            if (!$endDate) {
                $endDate = $startDate->copy();
            }

            // If not all-day, we have a start time, and no end time → +4h
            if (!$allDay && $startTime && !$endTime) {
                $tStart = Carbon::createFromFormat('H:i', $startTime);
                $tEnd   = $tStart->copy()->addHours(4);
                $endTime = $tEnd->format('H:i');
            }
        }

        centerRequests::create([
            'event_type'       => $type,
            'event_date'       => $startDate->toDateString(),
            'end_date'         => $endDate?->toDateString(),
            'event_time'       => $startTime,
            'end_time'         => $endTime,
            'all_day'          => $allDay,
            'requested_by'     => $validated['requesterEmail'],
            'event_description'=> $validated['eventDescription'],
        ]);

        return redirect()->back()->with('success', 'Your event request has been submitted!');
    }

public function moderate(centerRequests $request, Request $http)
{
    $data = $http->validate([
        'decision'     => 'required|in:approved,rejected',
        'review_notes' => 'nullable|string|max:2000',
    ]);

    DB::transaction(function () use ($request, $data) {
        // 1) Moderation fields
        $request->status       = $data['decision'];
        $request->review_notes = $data['review_notes'] ?? null;
        $request->reviewed_by  = Auth::id();
        $request->reviewed_at  = now();

        // 2) On approve → create calendar event
        if ($data['decision'] === 'approved') {
            $title = $request->event_type . ' — ' . $request->title;

            // Unique slug
            $slug = Str::slug($title);
            $base = $slug; $i = 2;
            while (Events::where('slug', $slug)->exists()) {
                $slug = "{$base}-{$i}";
                $i++;
            }

            $startDate = Carbon::parse($request->event_date);
            $endDate   = $request->end_date
                        ? Carbon::parse($request->end_date)
                        : $startDate->copy();
            $allDay    = (bool) $request->all_day;

            if ($allDay) {
                // All-day: span whole days
                $startAt = $startDate->copy()->startOfDay();
                $endAt   = $endDate->copy()->endOfDay();
            } else {
                // Timed event
                $timeStart = $request->event_time ?: '00:00';
                $timeEnd   = $request->end_time;

                $startAt = Carbon::parse(
                    $startDate->format('Y-m-d') . ' ' . $timeStart
                );

                if ($timeEnd) {
                    $endAt = Carbon::parse(
                        $endDate->format('Y-m-d') . ' ' . $timeEnd
                    );
                } else {
                    // Fallback: +4h
                    $endAt = $startAt->copy()->addHours(4);
                }
            }

            Events::create([
                'title'        => $title,
                'slug'         => $slug,
                'description'  => $request->event_description,
                'event_type'   => $request->event_type,
                'requested_by' => $request->requested_by,
                'start_at'     => $startAt,
                'end_at'       => $endAt,
                'all_day'      => $allDay,
                'status'       => 'published',
                'visibility'   => 'public',
                // 'created_by' => Auth::id(), // if you add this column
            ]);
        }

        $request->save();
    });

    return back()->with('success', "Request {$data['decision']} successfully.");
}


}
