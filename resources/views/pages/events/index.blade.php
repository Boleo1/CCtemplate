<x-app-layout>
  <x-header />
  <div class="eventList">
    <h2 class="eventsHeading">Upcoming Events</h2>

    @if($events->isEmpty())
      <p>No upcoming events yet.</p>
    @else
      <ul class="events-grid">
        @foreach ($events as $event)
          <li class="event-card">
            @if ($event->thumbnail_image_path)
              <img
                src="{{ asset('storage/' . $event->thumbnail_image_path) }}"
                alt="{{ $event->title }}"
                class="event-thumbnail"
                loading="lazy"
              >
            @else
              <img
                src="{{ asset('images/placeholder-thumb.jpg') }}"
                alt="No thumbnail"
                class="event-thumbnail"
                loading="lazy"
              >
            @endif

            <h3 class="event-title">{{ $event->title ?? 'No Title' }}</h3>

            <p class="event-meta">
              {{ \Carbon\Carbon::parse($event->start_at)->format('F j, Y') }}
              at
              {{ \Carbon\Carbon::parse($event->start_at)->format('g:i A') }}
              • {{ $event->event_type ?? 'General' }}
            </p>

            <p class="event-desc">
              {{ \Illuminate\Support\Str::limit(strip_tags($event->description), 140) }}
            </p>
            
            <div class="event-actions">
              <a href="{{ route('events.show', $event->slug) }}" class="btn btn-primary">View details</a>
            </div>
          </li>
        @endforeach
      </ul>
    @endif
  </div>
</x-app-layout>
