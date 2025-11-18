document.addEventListener('DOMContentLoaded', () => {
  const el = document.getElementById('calendar');
  if (!el || !window.FullCalendar) return;

  const isMobile = window.matchMedia('(max-width: 768px)').matches;

  const cal = new FullCalendar.Calendar(el, {
    initialView: isMobile ? 'listWeek' : 'dayGridMonth',

    height: 'auto',
    expandRows: true,
    fixedWeekCount: false,
    timeZone: 'local',

    headerToolbar: isMobile
      ? {
          left: 'title',
          right: 'today prev,next',
        }
      : {
          left: 'dayGridMonth,timeGridWeek,listWeek',
          center: 'title',
          right: 'prev,next today',
        },

    titleFormat: { year: 'numeric', month: 'long' },
    dayHeaderFormat: { weekday: 'short' },

    events: '/api/events',
    eventTimeFormat: {
      hour: 'numeric',
      minute: '2-digit',
      meridiem: 'short',
    },
    displayEventEnd: true,

    windowResize() {
      const mobileNow = window.innerWidth <= 768;
      const current = cal.view.type;

      if (mobileNow && current !== 'listWeek') {
        cal.changeView('listWeek');
      } else if (!mobileNow && current === 'listWeek') {
        cal.changeView('dayGridMonth');
      }
    },
  });

  cal.render();
});
