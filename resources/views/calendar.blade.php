<x-app-layout>
  <x-header>

  </x-header>
{{-- <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js'></script>
    <script>

      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth'
        });
        calendar.render();
      });

    </script>
    <body>
      <div id='calendar'></div>
    </body> --}}


    <!-- resources/views/calendar.blade.php -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js"></script>
<div id="calendar" class="min-h-[700px]"></div>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const cal = new FullCalendar.Calendar(document.getElementById('calendar'), {
    initialView: 'dayGridMonth',
    timeZone: 'local',
    events: '/api/events',
    eventTimeFormat: { hour: '2-digit', minute: '2-digit', meridiem: true },
  });
  cal.render();
});
</script>

</x-app-layout>