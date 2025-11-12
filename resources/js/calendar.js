document.addEventListener('DOMContentLoaded', () => {
  const el = document.getElementById('calendar');
  if (!el || !window.FullCalendar) return;

  const cal = new FullCalendar.Calendar(el, {
    initialView: 'dayGridMonth',
    height: 'auto',
    expandRows: true,
    fixedWeekCount: false,
    timeZone: 'local',
    headerToolbar: { left: 'title', right: 'today prev,next' },
    titleFormat: { year: 'numeric', month: 'long' },
    dayHeaderFormat: { weekday: 'short' },
    events: '/api/events',
    eventTimeFormat: { hour: 'numeric', minute: '2-digit', meridiem: 'short' },
  });
  cal.render();
});
