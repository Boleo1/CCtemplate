<x-app-layout>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.css">
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js"></script>

  <div id="calendar" style="min-height:700px"></div>


  @vite(['resources/js/calendar.js'])

  @php
    $today = \Carbon\Carbon::today()->format('Y-m-d');
    $max = \Carbon\Carbon::today()->addYear()->format('Y-m-d');
  @endphp

<section class="calendarRequestCenter">
  <h2>Request an Event</h2>
  <p>If you would like to request an event to be added to our community center calendar, please fill out the form below with the event details. Our team will review your request.</p>
  
  <x-ui.request-form :today="$today" :max="$max" />
</section>


</x-app-layout>