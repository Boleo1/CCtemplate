<x-dashboard-layout>
  <div>
    <h1>Events</h1>
  </div>

  <div class='events-form-container'>
    <x-event-form />

  </div>

  <div class="events-list-container">
    <h3>Existing Events</h3>
      <ul>
        @foreach($events as $event)
          @if(is_array($events) || is_object($event))
          <li>
            <h3>{{ $event->title ?? 'No Title' }}</h3>
            <p>{{ $event->description }}</p>
            <p>{{ $event->start_at }} to {{ $event->end_at }}</p>
            <p>Type: {{ $event->type }}</p>
          </li>
          @endif
        @endforeach 
      </ul>


</x-dashboard-layout>