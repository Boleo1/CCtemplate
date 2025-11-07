<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Events;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\EventGalleryImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class adminEventController extends Controller
{
    public function index()
    {
        $events = Events::orderBy('sort_order')->orderByDesc('created_at')->paginate(25);
        return view('dashboard.events.index', compact('events'));
    }

    public function create()
    {
        return view('dashboard.events.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'eventName'         => 'required|string|max:255',
            'eventDate'         => 'required|date',
            'eventTime'         => 'required',
            'eventType'         => 'required|string|max:100',
            'eventDescription'  => 'required|string',
            // images
            'thumbnail_image_path'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'gallery.*'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
        ]);

        // slug (unique-ish)
        $base = Str::slug($data['eventName'], '-');
        $slug = $base;
        $i = 2;
        while (Events::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }
        $thumbPath = $request->hasFile('thumbnail_image_path')
        ? $request->file('thumbnail_image_path')->store('events/thumbnails', 'public')
        : null;

        $event = Events::create([
            'title'        => $data['eventName'],
            'start_at'     => $data['eventDate'] . ' ' . $data['eventTime'],
            'event_type'   => $data['eventType'],
            'slug'         => $slug,
            'thumbnail_image_path'    => $thumbPath,
        ]);

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $img) {
                $path = $img->store('events/gallery', 'public');
                EventGalleryImage::create([
                    'event_id'   => $event->id,
                    'image_path' => $path,
                ]);
            }
        }
        return redirect()->route('admin.events.index')->with('success', 'Event created.');
    }

    public function edit(Events $event)
    {
        return view('dashboard.events.edit', compact('event'));
    }

    public function update(Request $request, Events $event)
    {
        $data = $request->validate([
            'eventName'        => ['required','string','max:255'],
            'eventDate'     => ['required','date'],
            'eventTime'     => ['required','date'],
            'event_type'   => ['required','string','max:100'],
            'eventDescription'  => ['required','string'],
            'thumbnail_image_path'    => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
        ]);
        if ($request->hasFile('thumbnail_image_path')) {
            $data['thumbnail_image_path'] = $request->file('thumbnail_image_path')->store('events/thumbnails', 'public');
        }

        $event->update($data);
        return back()->with('success', 'Event updated.');
    }

    public function destroy(Events $event)
    {
        $event->delete();
        return back()->with('success', 'Event deleted.');
    }

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

    // Optional: upload gallery after event
    public function galleryStore(Request $request, Events $event)
    {
        $request->validate(['gallery.*' => ['required','image','mimes:jpg,jpeg,png,webp','max:8192']]);

        foreach ($request->file('gallery', []) as $img) {
            $path = $img->store('events/gallery', 'public');
            $event->galleryImages()->create(['image_path' => $path]);
        }
        return back()->with('success', 'Gallery updated.');
    }
}
