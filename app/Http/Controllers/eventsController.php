<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Events;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;


// Events Controller to handle public event pages
class eventsController extends Controller
{
public function index(Request $request)
{
    $types = [
        'all'              => 'All',
        'Community Event'  => 'Community Events',
        'Class/Program'    => 'Programs',
        'Sports'           => 'Sports',
        'Rental'           => 'Rentals',
        'Facility Closure' => 'Closures',
        'Notice'           => 'Notices',
        'Wake'             => 'Wakes',
    ];

    $activeType = $request->query('type', 'all');
    $now = now();

    $query = Events::query()
        ->where('end_at', '>=', $now)
        ->orderByRaw(
            "CASE WHEN start_at <= ? AND end_at >= ? THEN 0 ELSE 1 END",
            [$now, $now]
        )
        ->orderBy('start_at');

    if ($activeType !== 'all') {
        $query->where('event_type', $activeType);
    }

    $events = $query->paginate(9)->withQueryString();

    return view('pages.events.index', [
        'events'     => $events,
        'pageTitle'  => 'Events',
        'types'      => $types,
        'activeType' => $activeType,
    ]);
}

    public function queryPast(Request $request){

    }
 public function past(Request $request)
{
    $types = [
        'all'              => 'All',
        'Community Event'  => 'Community Events',
        'Class/Program'    => 'Programs',
        'Sports'           => 'Sports',
        'Rental'           => 'Rentals',
        'Facility Closure' => 'Closures',
        'Notice'           => 'Notices',
        'Wake'             => 'Wakes',
    ];

    $activeType = $request->query('type', 'all');
    $monthParam = $request->query('month'); // YYYY-MM (optional)
    $now = now();

    // Default month = current month (better UX for month-by-month browsing)
    try {
        $monthDate = $monthParam
            ? Carbon::createFromFormat('Y-m', $monthParam)
            : now();
    } catch (\Exception $e) {
        $monthDate = now();
    }

    $monthStart = (clone $monthDate)->startOfMonth();
    $monthEnd   = (clone $monthDate)->endOfMonth();

    $prevMonth = (clone $monthStart)->subMonth()->format('Y-m');
    $nextMonth = (clone $monthStart)->addMonth()->format('Y-m');

    // Optional: don’t allow “Next” past the current month (since this is Past Events)
    $disableNext = $monthStart->greaterThanOrEqualTo(now()->startOfMonth());

    $query = Events::query()
        // Past definition: ended already OR (no end_at) started already
        ->where(function ($q) use ($now) {
            $q->where(function ($q1) use ($now) {
                $q1->whereNotNull('end_at')
                   ->where('end_at', '<', $now);
            })->orWhere(function ($q2) use ($now) {
                $q2->whereNull('end_at')
                   ->where('start_at', '<', $now);
            });
        })
        // Month archive filter: based on start_at
        ->whereBetween('start_at', [$monthStart, $monthEnd]);

    if ($activeType !== 'all') {
        $query->where('event_type', $activeType);
    }

    $events = $query
        ->orderBy('start_at', 'desc')
        ->paginate(9)
        ->withQueryString();

    return view('pages.events.past', [
        'events'      => $events,
        'pageTitle'   => 'Past Events',
        'types'       => $types,
        'activeType'  => $activeType,
        'month'       => $monthStart->format('Y-m'),
        'monthLabel'  => $monthStart->format('F Y'),
        'prevMonth'   => $prevMonth,
        'nextMonth'   => $nextMonth,
        'disableNext' => $disableNext,
    ]);
}



    public function show($slug) {
      $event = Events::where('slug', $slug)->with('galleryImages')->firstOrFail();

      return view('pages.events.show', compact('event'));
    }
}
