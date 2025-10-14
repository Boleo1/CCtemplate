<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\centerRequests;
use Illuminate\Support\Str;

class eventsRequestController extends Controller
{
  public function submit(Request $request)
    {
      // dd($request->all());
        $validated = $request->validate([
            'eventType' => 'required|string',
            'date' => 'required|date',
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
}
