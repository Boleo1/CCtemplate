<x-dashboard-layout>
  <div class="events-page-header">
    <h1>Events</h1>
  </div>

  <div class="events-layout">

    {{-- ===== Existing Events (scrollable panel) ===== --}}
    <section class="events-list-container">
      <div class="events-list-head">
        <h3>Existing Events</h3>
      </div>

      <div class="events-list-scroll">
        @if($events->isEmpty())
          <p class="muted">No events created yet.</p>
        @else
          <ul class="events-list">
            @foreach($events as $event)
              <li class="events-list-item">
                <div class="events-list-body">
                  <div class="events-list-header">
                    <h4>{{ $event->title ?? 'No Title' }}</h4>
                    <span class="badge">{{ $event->event_type ?? 'General' }}</span>
                  </div>

                  <p class="events-list-meta">
                    {{ \Carbon\Carbon::parse($event->start_at)->format('F j, Y g:i A') }}
                    @if($event->end_at)
                      – {{ \Carbon\Carbon::parse($event->end_at)->format('g:i A') }}
                    @endif
                  </p>

                  <p class="events-list-desc">
                    {{ \Illuminate\Support\Str::limit(strip_tags($event->description), 140) }}
                  </p>
                </div>

                @auth
                  <div class="events-list-footer">
                    <a href="{{ route('admin.events.edit', $event->id) }}"
                       class="btn btn-secondary btn-sm">
                      Edit Event
                    </a>
                  </div>
                @endauth
              </li>
            @endforeach
          </ul>
        @endif
      </div>

      <div class="events-list-pagination">
        {{ $events->links() }}
      </div>
    </section>

    {{-- ===== Create Event ===== --}}
    <section class="adminCreateEvent">
      <h3>Create New Event</h3>
      <x-event.event-form />
    </section>

  </div>
</x-dashboard-layout>
