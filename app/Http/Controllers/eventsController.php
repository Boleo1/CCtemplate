<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Events;
use Illuminate\Http\Request;

class eventsController extends Controller
{
    public function index() {
      $events = Events::latest()->paginate(10);
      return view('events', ['events' => $events ]);
    }
}
