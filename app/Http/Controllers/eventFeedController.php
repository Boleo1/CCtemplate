<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Events;
use Illuminate\Http\Request;
use Carbon\Carbon;

class eventFeedController extends Controller
{
    public function index(Request $request)
    {
        // FullCalendar usually passes ?start=...&end=...
        $rangeStart = $request->query('start')
            ? Carbon::parse($request->query('start'))->startOfDay()
            : now()->copy()->startOfMonth()->startOfDay();

        $rangeEnd = $request->query('end')
            ? Carbon::parse($request->query('end'))->endOfDay()
            : now()->copy()->endOfMonth()->endOfDay();

        // Only fetch events that overlap the visible range
        $events = Events::query()
            ->where('start_at', '<=', $rangeEnd)
            ->where('end_at', '>=', $rangeStart)
            ->orderBy('start_at')
            ->get();

        $out = [];

        foreach ($events as $e) {
            $allDay = (bool) $e->all_day;

            // Safety: ensure we always have an end to work with
            $eventEnd = $e->end_at ?? $e->start_at;

            $shouldSplit =
                (bool) ($e->split_daily ?? false) &&
                !$allDay &&
                $e->start_at &&
                $eventEnd &&
                $e->start_at->toDateString() !== $eventEnd->toDateString();

            if ($shouldSplit) {
                // Use the TIME window from the saved datetimes
                $startTime = $e->start_at->format('H:i:s');
                $endTime   = $eventEnd->format('H:i:s');

                // Split across days from start->end, but clamp to requested range
                $startDay = $e->start_at->copy()->startOfDay();
                $endDay   = $eventEnd->copy()->startOfDay();

                $d = $startDay->copy()->max($rangeStart->copy()->startOfDay());
                $last = $endDay->copy()->min($rangeEnd->copy()->startOfDay());

                while ($d->lte($last)) {
                    $startAt = Carbon::parse($d->format('Y-m-d') . ' ' . $startTime);
                    $endAt   = Carbon::parse($d->format('Y-m-d') . ' ' . $endTime);

                    // If end time is earlier than start time, roll end into next day
                    if ($endAt->lte($startAt)) {
                        $endAt = $endAt->addDay();
                    }

                    $out[] = [
                        'id'     => $e->id . '-' . $d->format('Ymd'), // unique per day instance
                        'title'  => $e->title,
                        'start'  => $startAt->toIso8601String(),
                        'end'    => $endAt->toIso8601String(),
                        'allDay' => false,
                        'url'    => route('events.show', $e->slug),
                        'extendedProps' => [
                            'parent_id'   => $e->id,
                            'event_type'  => $e->event_type,
                            'status'      => $e->status,
                            'visibility'  => $e->visibility,
                            'description' => $e->description,
                        ],
                    ];

                    $d->addDay();
                }

                continue;
            }

            // Normal (non-split) event output
            $start = $e->start_at?->toIso8601String();
            $end   = $eventEnd?->toIso8601String();

            // FullCalendar all-day events use an exclusive end date
            if ($allDay && $eventEnd) {
                $end = $eventEnd->copy()->addDay()->startOfDay()->toIso8601String();
            }

            $item = [
                'id'     => (string) $e->id,
                'title'  => $e->title,
                'start'  => $start,
                'allDay' => $allDay,
                'url'    => route('events.show', $e->slug),
                'extendedProps' => [
                    'parent_id'   => $e->id,
                    'event_type'  => $e->event_type,
                    'status'      => $e->status,
                    'visibility'  => $e->visibility,
                    'description' => $e->description,
                ],
            ];

            if ($end) {
                $item['end'] = $end;
            }

            $out[] = $item;
        }

        return response()->json($out);
    }
}
