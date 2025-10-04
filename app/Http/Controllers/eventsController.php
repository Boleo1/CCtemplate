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


    public function show($id) {
      $event = Events::findOrFail($id);
      return view('event', ['event' => $event ]);
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
        'created_by' => $validatedData['requestedBy'],
        'eventDate' => $validatedData['eventDate'],
        'eventTime' => $validatedData['eventTime'],
        'eventDescription' => $validatedData['eventDescription'],
        'eventType' => $validatedData['eventType'],
      ]);

      dd($request->all());
      return back()->with('success',"Data was submitted.");
    }
}
