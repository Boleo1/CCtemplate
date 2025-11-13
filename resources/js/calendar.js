document.addEventListener('DOMContentLoaded', () => {
  const el = document.getElementById('calendar');
  if (!el || !window.FullCalendar) return;

  const isMobile = window.matchMedia('(max-width: 768px)').matches;

  const cal = new FullCalendar.Calendar(el, {
    // 👇 Different default view depending on screen size
    initialView: isMobile ? 'listWeek' : 'dayGridMonth',

    height: 'auto',
    expandRows: true,
    fixedWeekCount: false,
    timeZone: 'local',

    // 👇 Toolbar adapts to mobile / desktop
    headerToolbar: isMobile
      ? {
          left: 'title',
          right: 'today prev,next',
        }
      : {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,listWeek',
        },

    titleFormat: { year: 'numeric', month: 'long' },
    dayHeaderFormat: { weekday: 'short' },

    events: '/api/events',
    eventTimeFormat: {
      hour: 'numeric',
      minute: '2-digit',
      meridiem: 'short',
    },

    // 👇 When you resize, swap views appropriately
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
