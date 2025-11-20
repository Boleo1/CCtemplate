<div id="form-container">
  @if ($errors->any())
    <div class="form-errors">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

    <form class="calendar-form" id="calendar-form" action="{{ route('events.request.submit') }}" method="POST">
      @csrf

      {{-- Event Name --}}
      <x-ui.input-label for="eventName">Event Name:</x-ui.input-label>
      <input type="text" id="eventName" name="eventName" placeholder="Enter the event name" required>

      {{-- E-Mail --}}
      <x-ui.input-label for="requesterEmail">Your E-Mail:</x-ui.input-label>
      <input type="email" id="requesterEmail" name="requesterEmail" placeholder="Enter a valid email address" required>
      

      {{-- Type --}}
      <x-ui.input-label for="eventType">Event Type:</x-ui.input-label>
      <select name="event_type" id="event_type">
        @foreach(['Community Event', 'Class/Program', 'Sports', 'Rental', 'Facility Closure', 'Notice', 'Wake'] as $type)
          <option value="{{ $type }}" 
            {{ old('event_type', $event->event_type ?? '') === $type ? 'selected' : '' }}>
            {{ $type }}
          </option>
        @endforeach
      </select>


      {{--  Date ----}}
      <x-ui.input-label for="date">Start Date:</x-ui.input-label>
      <input type="date" id="date" name="date" min="{{ $today }}" max="{{ $max }}" required>

      <x-ui.input-label for="endDate">End Date (optional):</x-ui.input-label>
      <input type="date" id="endDate" name="endDate" min="{{ $today }}" max="{{ $max }}">

      <label class="checkbox-inline">
        <input type="checkbox" id="allDay" name="allDay" value="1">
        All-day event
      </label>


      {{-- Time fields --}}
      <div class="time-row">
        <div class="time-field">
          <x-ui.input-label for="eventTime">Start Time:</x-ui.input-label>
          <select id="eventTime" name="eventTime">
            <option value="">Select a time...</option>
            @for ($h = 6; $h <= 22; $h++)
              <option value="{{ sprintf('%02d:00', $h) }}">{{ date('g:i A', mktime($h, 0)) }}</option>
              <option value="{{ sprintf('%02d:30', $h) }}">{{ date('g:i A', mktime($h, 30)) }}</option>
            @endfor
          </select>
        </div>

        <div class="time-field">
          <x-ui.input-label for="endTime">End Time (optional):</x-ui.input-label>
          <select id="endTime" name="endTime">
            <option value="">Select a time...</option>
            @for ($h = 6; $h <= 22; $h++)
              <option value="{{ sprintf('%02d:00', $h) }}">{{ date('g:i A', mktime($h, 0)) }}</option>
              <option value="{{ sprintf('%02d:30', $h) }}">{{ date('g:i A', mktime($h, 30)) }}</option>
            @endfor
          </select>
        </div>
      </div>

      {{-- Description --}}
      <x-ui.input-label for="eventDescription">Additional Details:</x-ui.input-label>
      <textarea id="eventDescription" name="eventDescription" rows="4" placeholder="Additional event details" required></textarea>
      

      {{-- Buttons --}}
      <x-ui.button class="btn-primary" type="submit">Submit</x-ui.button>
      <x-ui.button class="btn-secondary" type="button" id="nav-form-cancel-btn">Cancel</x-ui.button>
    </form>
  </div>