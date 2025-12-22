<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Events;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;


class eventsController extends Controller
{
    public function index(Request $request)
    {
        $types = [
            'all'             => 'All',
            'Community Event' => 'Community Events',
            'Class/Program'   => 'Programs',
            'Sports'          => 'Sports',
            'Rental'          => 'Rentals',
            'Facility Closure'=> 'Closures',
            'Notice'          => 'Notices',
            'Wake'            => 'Wakes',
        ];

        $activeType = $request->query('type', 'all');

        $query = Events::query()->orderBy('start_at');

        if ($activeType !== 'all') {
            $query->where('event_type', $activeType);
        }

        $events = $query->paginate(9)->withQueryString();

        $pageTitle = 'Events';

        return view('pages.events.index', [
            'events'     => $events,
            'pageTitle'  => $pageTitle,
            'types'      => $types,
            'activeType' => $activeType,
        ]);
    }


    public function show($slug) {
      $event = Events::where('slug', $slug)->with('galleryImages')->firstOrFail();

      return view('pages.events.show', compact('event'));
    }
}
