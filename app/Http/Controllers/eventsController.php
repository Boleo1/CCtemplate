<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Events;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;


class eventsController extends Controller
{
    public function index() {
      $events = Events::latest()->paginate(10);
      $pageTitle = 'Events';
      return view('pages.events.index', ['events' => $events , 'pageTitle' => $pageTitle]);
    }

    public function show($slug) {
      $event = Events::where('slug', $slug)->with('galleryImages')->firstOrFail();

      return view('pages.events.show', compact('event'));
    }
}
