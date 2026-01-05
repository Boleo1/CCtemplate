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


// Controller for admin event management
class adminEventController extends Controller
{
  public function index(Request $request)
  {
      $filter = $request->query('filter', 'active'); // active | deleted | all

      $query = Events::query();

      if ($filter === 'deleted') {
          $query->onlyTrashed();
      } elseif ($filter === 'all') {
          $query->withTrashed();
      } // active = default (no trashed)

      $events = $query
          ->orderBy('sort_order')
          ->orderByDesc('created_at')
          ->paginate(25)
          ->withQueryString();

      return view('dashboard.events.index', compact('events', 'filter'));
  }


  public function create()
  {
      return view('dashboard.events.create');
  }

  public function store(Request $request)
  {
      $data = $request->validate([
          'title'                => 'required|string|max:255',
          'start_date'           => 'required|date',
          'end_date'             => 'nullable|date|after_or_equal:start_date',
          'start_time'           => 'nullable|date_format:H:i',
          'end_time'             => 'nullable|date_format:H:i',
          'all_day'              => 'nullable|boolean',
          'split_daily'          => 'nullable|boolean',
          'event_type'           => 'required|string|max:100',
          'description'          => 'required|string',
          'thumbnail_image_path' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
          'gallery.*'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
      ]);

      // If "split daily" is checked, require proper inputs for that mode
      if ($request->boolean('split_daily')) {
          $request->validate([
              'end_date'    => ['required','date','after:start_date'],
              'start_time'  => ['required','date_format:H:i'],
              'end_time'    => ['required','date_format:H:i'],
          ]);

          if ($request->boolean('all_day')) {
              return back()
                  ->withErrors(['split_daily' => 'Split daily can’t be used with All Day events.'])
                  ->withInput();
          }
      }

      // Generate slug (unique-ish)
      $base = Str::slug($data['title'], '-');
      $slug = $base;
      $i = 2;
      while (Events::where('slug', $slug)->exists()) {
          $slug = "{$base}-{$i}";
          $i++;
      }

      // Build date/times (matches update() behavior)
      $allDay = $request->boolean('all_day');

      $startDate = Carbon::parse($data['start_date']);

      $endDateProvided = !empty($data['end_date']);
      $endDate = $endDateProvided ? Carbon::parse($data['end_date']) : $startDate->copy();

      $startTime = $data['start_time'] ?? null;
      $endTime   = $data['end_time'] ?? null;

      if ($allDay) {
          $startAt = $startDate->copy()->startOfDay();
          $endAt   = $endDate->copy()->endOfDay();
      } else {
          $timeStartStr = $startTime ?: '00:00';
          $startAt = Carbon::parse($startDate->format('Y-m-d') . ' ' . $timeStartStr);

          if ($endTime) {
              $endAt = Carbon::parse($endDate->format('Y-m-d') . ' ' . $endTime);
          } else {
              // If end_date exists, respect it even without end_time; otherwise default duration
              $endAt = $endDateProvided
                  ? $endDate->copy()->endOfDay()
                  : $startAt->copy()->addHours(4);
          }
      }

      // Thumbnail upload
      $thumbPath = $request->hasFile('thumbnail_image_path')
          ? $request->file('thumbnail_image_path')->store('events/thumbnails', 'public')
          : null;

      // Create event
      $event = Events::create([
          'title'                => $data['title'],
          'slug'                 => $slug,
          'event_type'           => $data['event_type'],
          'description'          => $data['description'],
          'start_at'             => $startAt,
          'end_at'               => $endAt,
          'all_day'              => $allDay,
          'split_daily'          => $request->boolean('split_daily', false),
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

      // Gallery uploads
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

  public function update(Request $request, Events $event)
  {
    $data = $request->validate([
      'title'                => ['required','string','max:255'],
      'start_date'           => ['required','date'],
      'end_date'             => ['nullable','date','after_or_equal:start_date'],
      'start_time'           => ['nullable','date_format:H:i'],
      'end_time'             => ['nullable','date_format:H:i'],
      'all_day'              => ['nullable','boolean'],
      'event_type'           => ['required','string','max:100'],
      'description'          => ['string'],
      'thumbnail_image_path' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
      'split_daily'          => ['nullable','boolean'],
    ]);
    
    $allDay = $request->boolean('all_day');
    
    $startDate = Carbon::parse($data['start_date']);
    
    // Track whether user actually provided an end_date
    $endDateProvided = !empty($data['end_date']);
    $endDate = $endDateProvided ? Carbon::parse($data['end_date']) : $startDate->copy();
    
    $startTime = $data['start_time'] ?? null;
    $endTime   = $data['end_time']   ?? null;
    
    if ($allDay) {
      $startAt = $startDate->copy()->startOfDay();
      $endAt   = $endDate->copy()->endOfDay();
    } else {
      $timeStartStr = $startTime ?: '00:00';
      $startAt = Carbon::parse($startDate->format('Y-m-d') . ' ' . $timeStartStr);
      
      if ($endTime) {
        $endAt = Carbon::parse($endDate->format('Y-m-d') . ' ' . $endTime);
      } else {
        // ✅ FIX: if end_date exists, respect it even without end_time
        $endAt = $endDateProvided
        ? $endDate->copy()->endOfDay()
        : $startAt->copy()->addHours(4);
      }
    }
    
    $updateData = [
      'title'       => $data['title'],
      'event_type'  => $data['event_type'],
      'description' => $data['description'],
      'start_at'    => $startAt,
      'end_at'      => $endAt,
      'all_day'     => $allDay,
      'split_daily' => $request->boolean('split_daily', false),
    ];
    
    if ($request->hasFile('thumbnail_image_path')) {
      if ($event->thumbnail_image_path) {
        Storage::disk('public')->delete($event->thumbnail_image_path);
      }
      $updateData['thumbnail_image_path'] = $request
      ->file('thumbnail_image_path')
      ->store('events/thumbnails','public');
    }
    
    // dd([
      //     'incoming_start_date' => $data['start_date'],
      //     'incoming_end_date'   => $data['end_date'] ?? null,
      //     'computed_start_at'   => $startAt->toDateTimeString(),
      //     'computed_end_at'     => $endAt->toDateTimeString(),
      //     'updateData'          => $updateData,
      // ]);
      
      $event->update($updateData);
      
      return back()->with('success', 'Event updated.');
  }
  
  public function edit(Events $event)
  {
      return view('dashboard.events.edit', compact('event'));
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

  public function galleryStore(Request $request, Events $event)
  {
      $request->validate(['gallery.*' => ['required','image','mimes:jpg,jpeg,png,webp','max:8192']]);

      foreach ($request->file('gallery', []) as $img) {
          $path = $img->store('events/gallery', 'public');
          $event->galleryImages()->create(['image_path' => $path]);
      }
      return back()->with('success', 'Gallery updated.');
  }

  public function restore($id)
  {
      Events::withTrashed()->findOrFail($id)->restore();
      return back()->with('success', 'Event restored.');
  }

}
