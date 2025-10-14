@props(['links' => [], 'showAuthLinks' => true])

<nav {{ $attributes->class(['nav-bar']) }}>
  <ul>
      @foreach($links as $link)
      <li>
        <a href="{{ $link['url'] }}" class="{{ $link['class'] ?? '' }}" id="{{ $link['id'] ?? '' }}">{{ $link['label'] }}</a>
        @endforeach
      </li>
      <li>
        @if(Auth::check() && $showAuthLinks)
        <a href="/dashboard" class="navDashboard">Dashboard</a>
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <x-button class="btn-secondary">
              Logout
            </x-button>
          </form>
      </li>
        @endif
      <button id="nav-form-toggle-btn" type="button" class="btn-primary">Create Event</button>
  
  </ul>
</nav>

{{-- Form For Navigation --}}
<div id="nav-form-container" >
  <form class="nav-form" id="nav-form" action="{{ route('events.request.submit') }}" method="POST" >
    @csrf
    <label for="eventType">Event Type:</label>
      <select name="eventType" id="eventType" required>
        <option value="Activity">Activity</option>
        <option value="Sports">Sports</option>
        <option value="Cultural">Cultural</option>
        <option value="Class">Class</option>
        <option value="Sale">Sale</option>
        <option value="Wake">Wake</option>
      </select>
    <label for="date">Event Date:</label>
    <input type="date" id="date" name="date" required>

    <label for="eventTime">Event Time:</label>
    <input type="time" id="eventTime" name="eventTime" required>

    <label for="requesterEmail">Your E-Mail:</label>
    <input type="email" id="requesterEmail" name="requesterEmail" required>
    
    <label for="eventDescription">Additional Details:</label>
    <textarea id="eventDescription" name="eventDescription" rows="4" required></textarea>
    
    <x-button class="btn-primary" type="submit">Submit</x-button>
    <x-button class="btn-secondary" type="button" id="nav-form-cancel-btn">Cancel</x-button>
  </form>
</div>