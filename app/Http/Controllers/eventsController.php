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
        'title' => 'required|string|',
        'description' => 'required|string',
        'challenges' => 'nullable|string',
        'livelink' => 'nullable|url',
        'github' => 'nullable|url',
        'image' => 'nullable|url',
      ]);

      Events::create([
        'title' => $validatedData['title'],
        'description' => $validatedData['description'],
        'challenges' => $validatedData['challenges'],
        'livelink' => $validatedData['livelink'],
        'github' => $validatedData['github'],
        'image' => $validatedData['image'] ?? null,
      ]);
      return back()->with('success',"Data was submitted.");
    }
}
