<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Events;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
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
        'title'              => 'required|string|max:255',
        'start_date'         => 'required|date',
        'end_date'           => 'nullable|date|after_or_equal:start_date',
        'start_time'         => 'nullable|date_format:H:i',
        'end_time'           => 'nullable|date_format:H:i',
        'all_day'            => 'nullable|boolean',
        'event_type'         => 'required|string|max:100',
        'description'        => 'required|string',
        'thumbnail_image_path' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        'gallery.*'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
    ]);

    // slug (unique-ish)
    $base = Str::slug($data['title'], '-');
    $slug = $base;
    $i = 2;
    while (Events::where('slug', $slug)->exists()) {
        $slug = "{$base}-{$i}";
        $i++;
    }

    $allDay    = $request->boolean('all_day');
    $startDate = Carbon::parse($data['start_date']);
    $endDate   = isset($data['end_date'])
               ? Carbon::parse($data['end_date'])
               : null;

    $startTime = $data['start_time'] ?? null;
    $endTime   = $data['end_time']   ?? null;

    if (!$endDate) {
        $endDate = $startDate->copy();
    }

    if ($allDay) {
        $startAt = $startDate->copy()->startOfDay();
        $endAt   = $endDate->copy()->endOfDay();
    } else {
        $timeStartStr = $startTime ?: '00:00';
        $startAt = Carbon::parse(
            $startDate->format('Y-m-d') . ' ' . $timeStartStr
        );

        if ($endTime) {
            $endAt = Carbon::parse(
                $endDate->format('Y-m-d') . ' ' . $endTime
            );
        } else {
            // default length: 4 hours
            $endAt = $startAt->copy()->addHours(4);
        }
    }

    $thumbPath = $request->hasFile('thumbnail_image_path')
        ? $request->file('thumbnail_image_path')->store('events/thumbnails', 'public')
        : null;

    $event = Events::create([
    'title'                => $data['title'],
    'slug'                 => $slug,
    'event_type'           => $data['event_type'],
    'description'          => $data['description'],
    'start_at'             => $startAt,
    'end_at'               => $endAt,
    'all_day'              => $allDay,
    'thumbnail_image_path' => $thumbPath,

    // REQUIRED by DB:
    'created_by'           => Auth::id(),
    'status'               => 'published',
    'visibility'           => 'public',
    'sort_order'           => 999999,

    // Optional fields
    'requested_by'         => null,
    'cover_image_path'     => null,
    'published_at'         => now(),
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
        'title'              => ['required','string','max:255'],
        'start_date'         => ['required','date'],
        'end_date'           => ['nullable','date','after_or_equal:start_date'],
        'start_time'         => ['nullable','date_format:H:i'],
        'end_time'           => ['nullable','date_format:H:i'],
        'all_day'            => ['nullable','boolean'],
        'event_type'         => ['required','string','max:100'],
        'description'        => ['string'],
        'thumbnail_image_path' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
    ]);

    $allDay    = $request->boolean('all_day');
    $startDate = Carbon::parse($data['start_date']);
    $endDate   = isset($data['end_date'])
               ? Carbon::parse($data['end_date'])
               : null;

    $startTime = $data['start_time'] ?? null;
    $endTime   = $data['end_time']   ?? null;

    if (!$endDate) {
        $endDate = $startDate->copy();
    }

    if ($allDay) {
        $startAt = $startDate->copy()->startOfDay();
        $endAt   = $endDate->copy()->endOfDay();
    } else {
        $timeStartStr = $startTime ?: '00:00';
        $startAt = Carbon::parse(
            $startDate->format('Y-m-d') . ' ' . $timeStartStr
        );

        if ($endTime) {
            $endAt = Carbon::parse(
                $endDate->format('Y-m-d') . ' ' . $endTime
            );
        } else {
            $endAt = $startAt->copy()->addHours(4);
        }
    }

    // Build update payload
    $updateData = [
        'title'       => $data['title'],
        'event_type'  => $data['event_type'],
        'description' => $data['description'],
        'start_at'    => $startAt,
        'end_at'      => $endAt,
        'all_day'     => $allDay,
    ];

    if ($request->hasFile('thumbnail_image_path')) {
        if ($event->thumbnail_image_path) {
            Storage::disk('public')->delete($event->thumbnail_image_path);
        }
        $updateData['thumbnail_image_path'] = $request
            ->file('thumbnail_image_path')
            ->store('events/thumbnails','public');
    }

    $event->update($updateData);

    return back()->with('success', 'Event updated.');
}


    public function destroy(Events $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully.');
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
