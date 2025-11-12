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
      // dd($request->all());
        $validated = $request->validate([
            'eventType' => 'required|string',
            'date' => 'required|date|after_or_equal:today',
            'eventTime' => 'required',
            'requesterEmail' => 'required|email',
            'eventDescription' => 'required|string|max:1000',
        ]);

        centerRequests::create([
            'event_type' => $validated['eventType'],
            'event_date' => $validated['date'],
            'event_time' => $validated['eventTime'],
            'requested_by' => $validated['requesterEmail'],
            'event_description' => $validated['eventDescription'],
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
        // 1) Update moderation fields
        $request->status       = $data['decision'];
        $request->review_notes = $data['review_notes'] ?? null;
        $request->reviewed_by  = Auth::id();
        $request->reviewed_at  = now();

        // 2) On approve -> create calendar event
        if ($data['decision'] === 'approved') {
            $title = $request->event_type . ' — ' . $request->requested_by;

            // Unique slug
            $slug  = Str::slug($title);
            $base  = $slug; $i = 2;
            while (Events::where('slug', $slug)->exists()) {
                $slug = "{$base}-{$i}";
                $i++;
            }

            // ---- Robust date/time normalization ----
            // event_date can be string or Carbon
            $eventDate = $request->event_date instanceof \DateTimeInterface
                ? Carbon::instance($request->event_date)
                : Carbon::parse($request->event_date);

            // event_time can be TIME string ("19:55" or "19:55:00") or Carbon or null
            if (empty($request->event_time)) {
                $timeStr = '00:00:00';
            } elseif ($request->event_time instanceof \DateTimeInterface) {
                $timeStr = Carbon::instance($request->event_time)->format('H:i:s');
            } else {
                // normalize any incoming time string to H:i:s
                $timeStr = Carbon::parse($request->event_time)->format('H:i:s');
            }

            // Build start/end datetimes
            $startAt = Carbon::parse($eventDate->format('Y-m-d') . ' ' . $timeStr);
            $endAt   = (clone $startAt)->addHour(); // adjust duration if needed

            Events::create([
                'title'        => $title,
                'slug'         => $slug,
                'description'  => $request->event_description,
                'event_type'   => $request->event_type,
                'requested_by' => $request->requested_by,
                'start_at'     => $startAt,
                'end_at'       => $endAt,
                'all_day'      => false,
                'status'       => 'published',
                'visibility'   => 'public',
                // 'created_by' => Auth::id(), // only if the column exists
            ]);
        }

        $request->save();
    });

    return back()->with('success', "Request {$data['decision']} successfully.");
}

}
