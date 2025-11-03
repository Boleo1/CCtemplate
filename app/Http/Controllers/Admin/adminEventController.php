<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Events;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class adminEventController extends Controller
{
    public function index()
    {
        // Show all events in admin with a sort column (see Step 4)
        $events = Events::orderBy('sort_order')->orderByDesc('created_at')->paginate(25);
        return view('dashboard.events.index', compact('events'));
    }

    public function update(Request $request, Events $event)
    {
        $data = $request->validate([
            'title'        => ['required','string','max:255'],
            'requested_by' => ['required','email'],
            'start_at'     => ['required','date'],
            'event_type'   => ['required','string','max:100'],
            'description'  => ['required','string'],
        ]);

        $event->update($data);
        return back()->with('success', 'Event updated.');
    }

    public function destroy(Events $event)
    {
        $event->delete();
        return back()->with('success', 'Event deleted.');
    }

    // Drag-and-drop reorder handler: expects an array of [id => position]
    public function reorder(Request $request)
    {
        $payload = $request->validate([
            'order' => ['required','array'],
            'order.*' => ['integer'],
        ]);

        foreach ($payload['order'] as $eventId => $position) {
            Events::where('id', $eventId)->update(['sort_order' => $position]);
        }

        return response()->json(['ok' => true]);
    }
}
