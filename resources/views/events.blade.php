<x-app-layout>
<x-header>
  <h1>Events</h1>
</x-header>
  <p> Events page where I will display events that are created from admin dashboard.</p>
    <div class="eventList">
      <h2 class="eventsHeading">Upcoming Events</h2>
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
    </div>
</x-app-layout>