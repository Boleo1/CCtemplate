<x-app-layout>
  @php
    $today = \Carbon\Carbon::today()->format('Y-m-d');
    $max = \Carbon\Carbon::today()->format('Y-m-d')
  @endphp
    <div class="events-mobile-switcher">
      <a
        href="{{ url('/events') }}"
        class="events-switch-btn {{ request()->is('events') ? 'is-active' : '' }}"
      >
        Upcoming
      </a>

      <a
        href="{{ url('/events/past') }}"
        class="events-switch-btn {{ request()->is('events/past') ? 'is-active' : '' }}"
      >
        Past
      </a>
    </div>
  <div class="eventList">
    <h2 class="eventsHeading">Past Events — {{ $monthLabel }}</h2>

    
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
        {{ $events->onEachSide(1)->links('pagination.pills') }}
      </div>
    @endif

    
    <div class="events-month-nav" style="display:flex; align-items:center; justify-content:flex-end; gap:.75rem; margin-bottom:1rem;">
      {{-- Previous month --}}
      <a
        class="btn btn-ghost"
        href="{{ route('events.past', array_filter([
          'month' => $prevMonth,
          'type'  => $activeType !== 'all' ? $activeType : null,
        ])) }}"
      >
        ‹ Prev
      </a>

      {{-- Current month label --}}
      <div class="events-month-label" style="font-weight:600;">
        {{ $monthLabel }}
      </div>

      {{-- Next month (disabled if at current month) --}}
      @if ($disableNext)
        <span class="btn btn-ghost" style="opacity:.5; pointer-events:none;">
          Next ›
        </span>
      @else
        <a
          class="btn btn-ghost"
          href="{{ route('events.past', array_filter([
            'month' => $nextMonth,
            'type'  => $activeType !== 'all' ? $activeType : null,
          ])) }}"
        >
          Next ›
        </a>
      @endif
    </div>


        
    @if($events->isEmpty())
      <p>No past events shown for {{ $monthLabel }}.</p>
    @else
    <ul class="events-list">
      @foreach ($events as $event)
      <li class="event-card" data-type="{{ $event->event_type }}">
        <div class="event-thumb {{ $event->thumbnail_image_path ? 'has-thumb' : 'no-thumb' }}">
          @if ($event->thumbnail_image_path)
            <a href="{{ asset('storage/' . $event->thumbnail_image_path) }}" class="lightbox-trigger">
              <img src="{{ asset('storage/' . $event->thumbnail_image_path) }}" alt="{{ $event->title }}" class="event-thumbnail" loading="lazy" >
            </a>
          @else
            <img src="{{ asset('storage/media/nothumbnail.png') }}" alt="No thumbnail" class="event-thumbnail" loading="lazy">
          @endif
        </div>

        <div class="event-body">
          <h3 class="event-title">{{ $event->title ?? 'No Title' }}</h3>
          <p class="events-list-meta">
            {{ \Carbon\Carbon::parse($event->start_at)->format('F j, Y g:i A') }}
            @if($event->end_at)
              – {{ \Carbon\Carbon::parse($event->end_at)->format('g:i A') }}
            @endif
          </p>
          <span class="event-type-badge">{{ $event->event_type }}</span>
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

    @if ($events->hasPages())
    <div class="events-pagination bottom">
      {{ $events->onEachSide(1)->links('pagination.pills') }}

    </div>
    @endif
  </div>
</x-app-layout>
