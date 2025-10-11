<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Events;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class eventsController extends Controller
{
    public function index() {
      $events = Events::latest()->paginate(10);
      $pageTitle = 'Events';
      return view('events', ['events' => $events , 'pageTitle' => $pageTitle]);
    }

    public function submit(Request $request) {

      $validatedData = $request->validate([
        'eventName' => 'required|string|',
        'requestedBy' => 'required|string|email',
        'eventDate' => 'required|date',
        'eventTime' => 'required',
        'eventDescription' => 'required|string',
        'eventType' => 'required|string',
      ]);

      Events::create([
        'title' => $validatedData['eventName'],
        'requested_by' => $validatedData['requestedBy'],
        'start_at' => $validatedData['eventDate'] . ' ' . $validatedData['eventTime'],
        'event_type' => $validatedData['eventType'],
        'description' => $validatedData['eventDescription'],
        'slug' => Str::slug($validatedData['eventName'], '-'),
      ]);

      return back()->with('success',"Data was submitted.");
    }
}
