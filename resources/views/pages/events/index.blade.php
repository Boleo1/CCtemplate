<x-app-layout>
  
  <div class="eventList">
    <h2 class="eventsHeading">Upcoming Events</h2>
    
    {{-- Filters --}}
    <div class="event-filters">
      @foreach ($types as $key => $label)
        @php 
          $isActive = $activeType === $key;
        @endphp
        <a href="{{ route('events.index', $key === 'all' ? [] : ['type' => $key]) }}" class="filter-pill {{ $isActive ? 'is-active' : '' }}">
          {{ $label }}
        </a>
      @endforeach
    </div>

    @if ($events->hasPages())
      <div class="events-pagination top">
        {{ $events->onEachSide(1)->links() }}
      </div>
    @endif

    <!-- Event List -->
    
    @if($events->isEmpty())
    <p>No upcoming events yet.</p>
    @else
    <!-- Event Cards -->
    <ul class="events-list">
      @foreach ($events as $event)
      <li class="event-card" data-type="{{ $event->event_type }}">

  <div class="event-thumb {{ $event->thumbnail_image_path ? 'has-thumb' : 'no-thumb' }}">
    
    @if ($event->thumbnail_image_path)
      <a href="{{ asset('storage/' . $event->thumbnail_image_path) }}" class="lightbox-trigger">
        <img
          src="{{ asset('storage/' . $event->thumbnail_image_path) }}"
          alt="{{ $event->title }}"
          class="event-thumbnail"
          loading="lazy"
        >
      </a>
    @else
      <img
        src="{{ asset('storage/media/nothumbnail.png') }}"
        alt="No thumbnail"
        class="event-thumbnail"
        loading="lazy"
      >
    @endif

  </div>

        
        <div class="event-body">
          <h3 class="event-title">{{ $event->title ?? 'No Title' }}</h3>
          <span class="event-type-badge">{{ $event->event_type }}</span>
          
          <p class="event-meta">
            {{ $event->start_at->format('F j, Y') }}
            @unless($event->all_day)
            at {{ $event->start_at->format('g:i A') }}
            @endunless
          </p>
          
          <p class="event-desc">
            {{Str::limit(strip_tags($event->description), 140) }}
          </p>
          
          <div class="event-actions">
            <a href="{{ route('events.show', $event->slug) }}" class="btn btn-primary">View details</a>
          </div>
        </div>
      </li>
      @endforeach
    </ul>
    @endif
    <div id="lightbox" hidden>
      <img id="lightbox-img" alt="">
    </div>
    
    <!-- Bottom pagination -->
    @if ($events->hasPages())
    <div class="events-pagination bottom">
      {{ $events->onEachSide(1)->links() }}
    </div>
    @endif
  </div>
</x-app-layout>
