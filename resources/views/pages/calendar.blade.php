<x-app-layout>
  <x-header />
 {{-- FullCalendar (CDN) --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.css">
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js"></script>

  <div id="calendar" style="min-height:700px"></div>

  {{-- your page JS (optional extra behaviors) --}}
  @vite(['resources/js/calendar.js'])

  @php
    $today = \Carbon\Carbon::today()->format('Y-m-d');
    $max = \Carbon\Carbon::today()->addYear()->format('Y-m-d');
    @endphp

<section class="calendarRequestCenter">
  <h2>Request an Event</h2>
  <p>If you would like to request an event to be added to our community center calendar, please fill out the form below with the event details. Our team will review your request.</p>
  
  <div id="form-container">
    <form class="calendar-form" id="calendar-form" action="{{ route('events.request.submit') }}" method="POST">
      @csrf
      <x-ui.input-label for="requesterEmail">Your E-Mail:</x-ui.input-label>
      <input type="email" id="requesterEmail" name="requesterEmail" placeholder="Enter a valid email address" required>
      
      {{-- Event Type --}}
      <x-ui.input-label for="eventType">Event Type:</x-ui.input-label>
      <select name="eventType" id="eventType" required>
        <option value="Activity">Activity</option>
        <option value="Sports">Sports</option>
        <option value="Cultural">Cultural</option>
        <option value="Class">Class</option>
        <option value="Sale">Sale</option>
        <option value="Wake">Wake</option>
      </select>

      {{-- Dates --}}
      <x-ui.input-label for="date">Start Date:</x-ui.input-label>
      <input type="date" id="date" name="date" min="{{ $today }}" max="{{ $max }}" required>

      <x-ui.input-label for="endDate">End Date (optional):</x-ui.input-label>
      <input type="date" id="endDate" name="endDate" min="{{ $today }}" max="{{ $max }}">

      {{-- All-day --}}
      <label class="checkbox-inline">
        <input type="checkbox" id="allDay" name="allDay" value="1">
        All-day event
      </label>

      {{-- Times --}}
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


      <x-ui.input-label for="eventDescription">Additional Details:</x-ui.input-label>
      <textarea id="eventDescription" name="eventDescription" rows="4" placeholder="Additional event details" required></textarea>
      
      <x-ui.button class="btn-primary" type="submit">Submit</x-ui.button>
      <x-ui.button class="btn-secondary" type="button" id="nav-form-cancel-btn">Cancel</x-ui.button>
    </form>
  </div>
</section>


</x-app-layout>