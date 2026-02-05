<x-dashboard-layout>
  @php
    $filters = [
      'active'  => 'Active',
      'deleted' => 'Deleted',
      'all'     => 'All',
    ];

    $activeFilter = $filter ?? request('filter', 'active');
  @endphp

  <div class="events-layout">

    {{-- ===== Existing Events (scrollable panel) ===== --}}
    <section class="events-list-container">
        <div class="events-list-head">
          <h3>Existing Events</h3>
          <div class="dashboard-event-filters">
            @foreach ($filters as $key => $label)
              @php $isActive = $activeFilter === $key; @endphp
  
              <a
                href="{{ route('admin.events.index', ['filter' => $key]) }}"
                class="filter-pill {{ $isActive ? 'is-active' : '' }}"
              >
                {{ $label }}
              </a>
            @endforeach
          </div>
        </div>




        <div class="events-list-scroll">
          @if($events->isEmpty())
            <p class="muted">No events created yet.</p>
          @else
            <ul class="events-list">
              @foreach($events as $event)
                @php
                  $isDeleted = method_exists($event, 'trashed') && $event->trashed();
                @endphp

                <li class="events-list-item {{ $isDeleted ? 'is-deleted' : '' }}">
                  <div class="events-list-body">
                    <div class="events-list-header">
                      <h4>{{ $event->title ?? 'No Title' }}</h4>

                      <div class="events-list-badges">
                        <span class="badge">{{ $event->event_type ?? 'General' }}</span>

                        @if($isDeleted)
                          <span class="badge badge-danger">Deleted</span>
                        @endif
                      </div>
                    </div>

                    <p class="events-list-meta">
                      {{ \Carbon\Carbon::parse($event->start_at)->format('F j, Y g:i A') }}
                      @if($event->end_at)
                        – {{ \Carbon\Carbon::parse($event->end_at)->format('g:i A') }}
                      @endif
                    </p>

                    <p class="events-list-desc">
                      {{ Str::limit(strip_tags($event->description), 140) }}
                    </p>
                  </div>

                  @auth
                    <div class="events-list-footer">
                      @if($isDeleted)
                        <form action="{{ route('admin.events.restore', $event->id) }}" method="POST">
                          @csrf
                          @method('PATCH')
                          <button type="submit" class="btn btn-secondary btn-sm">
                            Recover
                          </button>
                        </form>
                      @else
                        <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-secondary btn-sm">
                          Edit Event
                        </a>
                      @endif
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
