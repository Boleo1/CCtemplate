import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
  const form      = document.getElementById('admin-event-form');
  if (!form) return;

  const allDay    = form.querySelector('#all_day');
  const timeRow   = form.querySelector('.timeRow');
  const startTime = form.querySelector('#eventTime');
  const endTime   = form.querySelector('#endTime');
  const startDate = form.querySelector('#eventDate');
  const endDate   = form.querySelector('#endDate');

  const DEFAULT_DURATION_MIN = 240; // 4 hours; change if you want

  // we remember the "current" duration between start and end in minutes
  let durationMinutes = null;

  function timeToMinutes(timeStr) {
    if (!timeStr) return null;
    const [h, m] = timeStr.split(':').map(Number);
    return h * 60 + (m || 0);
  }

  function minutesToTime(totalMinutes) {
    if (totalMinutes == null) return '';
    // clamp to 0–23:59 so it doesn't wrap weirdly
    if (totalMinutes < 0) totalMinutes = 0;
    if (totalMinutes >= 24 * 60) totalMinutes = 24 * 60 - 1;

    const h = Math.floor(totalMinutes / 60);
    const m = totalMinutes % 60;
    const hh = String(h).padStart(2, '0');
    const mm = String(m).padStart(2, '0');
    return `${hh}:${mm}`;
  }

  function updateTimeRowVisibility() {
    if (!allDay || !timeRow || !startTime || !endTime) return;

    if (allDay.checked) {
      timeRow.style.display = 'none';
      startTime.value = '';
      endTime.value = '';
      durationMinutes = null; // reset
    } else {
      timeRow.style.display = 'flex';
    }
  }

  function syncEndDateDefault() {
    if (startDate && endDate && startDate.value && !endDate.value) {
      endDate.value = startDate.value;
    }
  }

  // When start time changes, recalc end time based on stored duration
  function handleStartTimeChange() {
    if (!startTime || !endTime || !startTime.value) return;

    const startMin = timeToMinutes(startTime.value);
    if (startMin == null) return;

    // figure out duration if we don't know it yet
    if (durationMinutes == null) {
      if (endTime.value) {
        const endMin = timeToMinutes(endTime.value);
        if (endMin != null && endMin > startMin) {
          durationMinutes = endMin - startMin;
        } else {
          durationMinutes = DEFAULT_DURATION_MIN;
        }
      } else {
        durationMinutes = DEFAULT_DURATION_MIN;
      }
    }

    const newEndMin = startMin + durationMinutes;
    endTime.value = minutesToTime(newEndMin);
  }

  // When end time changes, update the stored duration
  function handleEndTimeChange() {
    if (!startTime || !endTime || !startTime.value || !endTime.value) return;

    const startMin = timeToMinutes(startTime.value);
    const endMin   = timeToMinutes(endTime.value);
    if (startMin == null || endMin == null) return;

    const diff = endMin - startMin;
    durationMinutes = diff > 0 ? diff : DEFAULT_DURATION_MIN;
  }

  // Wire up listeners
  if (startTime) {
    startTime.addEventListener('change', handleStartTimeChange);
    startTime.addEventListener('input', handleStartTimeChange);
  }

  if (endTime) {
    endTime.addEventListener('change', handleEndTimeChange);
    endTime.addEventListener('input', handleEndTimeChange);
  }

  if (allDay) {
    allDay.addEventListener('change', updateTimeRowVisibility);
  }

  if (startDate && endDate) {
    startDate.addEventListener('change', syncEndDateDefault);
  }

  // Init on load
  updateTimeRowVisibility();
  syncEndDateDefault();
});




document.addEventListener('DOMContentLoaded', () => {
  const form       = document.getElementById('calendar-form');
  if (!form) return;

  const typeSelect = form.querySelector('#event_type');
  const startDate  = form.querySelector('#date');
  const endDate    = form.querySelector('#endDate');
  const allDay     = form.querySelector('#allDay');
  const timeRow    = form.querySelector('.time-row');
  const startTime  = form.querySelector('#eventTime');
  const endTime    = form.querySelector('#endTime');

  function addHoursToTimeString(timeStr, hours) {
    if (!timeStr) return '';
    const [h, m] = timeStr.split(':').map(Number);
    const d = new Date(2000, 0, 1, h, m || 0);
    d.setHours(d.getHours() + hours);
    const hh = String(d.getHours()).padStart(2, '0');
    const mm = String(d.getMinutes()).padStart(2, '0');
    return `${hh}:${mm}`;
  }

  function updateTimeRowVisibility() {
    if (allDay.checked || (typeSelect && typeSelect.value === 'Wake')) {
      timeRow.style.display = 'none';
      if (startTime) startTime.value = '';
      if (endTime)   endTime.value   = '';
    } else {
      timeRow.style.display = 'flex';
    }
  }

  /**
   * Sync end date + constraints based on start date.
   * - Normal events: end date is **always** same as start
   * - Wakes: end date is **min start+2 days**, but can be later
   */
  function setEndFromStart({ isWake = false } = {}) {
    if (!startDate || !endDate || !startDate.value) return;

    const base = new Date(startDate.value);
    if (isWake) {
      base.setDate(base.getDate() + 2);
    }

    const iso = base.toISOString().slice(0, 10);

    // update min so the picker blocks invalid days
    endDate.min = iso;

    if (isWake) {
      // For wakes: keep whatever the user chose as long as it’s >= min
      if (!endDate.value || endDate.value < iso) {
        endDate.value = iso;
      }
    } else {
      // For normal events: ALWAYS match start date exactly
      endDate.value = iso;
    }
  }

  function updateWakeLogic() {
    if (!typeSelect || !allDay) return;

    const isWake = typeSelect.value === 'Wake';

    if (isWake) {
      allDay.checked = true; // wakes default to all-day
      setEndFromStart({ isWake: true });
    } else {
      setEndFromStart({ isWake: false });
    }

    updateTimeRowVisibility();
  }

  // Auto +4h when end time is empty
  if (startTime) {
    startTime.addEventListener('change', () => {
      if (!endTime.value && startTime.value) {
        endTime.value = addHoursToTimeString(startTime.value, 4);
      }
    });
  }

  if (allDay) {
    allDay.addEventListener('change', updateTimeRowVisibility);
  }

  if (typeSelect) {
    typeSelect.addEventListener('change', updateWakeLogic);
  }

  if (startDate) {
    startDate.addEventListener('change', () => {
      const isWake = typeSelect && typeSelect.value === 'Wake';
      setEndFromStart({ isWake });
    });
  }

  // Init on load (for edit forms, etc.)
  updateWakeLogic();
});




// Flash message auto-hide
window.addEventListener('DOMContentLoaded', () => {
    const flash = document.getElementById('flash-message');
    if (!flash) return;
    const visibleMs = 2800;

    setTimeout(() => {
      flash.classList.add('is-hidden');
    }, visibleMs);

    setTimeout(() => {
      if (flash && flash.parentNode) {
        flash.parentNode.removeChild(flash);
      }
    }, visibleMs + 450);
  });
  //-- End Flash message auto-hide ----------------
 


// About Page - What we offer toggles
document.addEventListener('DOMContentLoaded', function () {
  const serviceItems = document.querySelectorAll('.service-item');

  serviceItems.forEach((item) => {
    const pill = item.querySelector('.service-pill');
    const panel = item.querySelector('.service-panel');
    if (!pill || !panel) return;

    pill.addEventListener('click', () => {
      const isOpen = item.classList.contains('is-open');

      // Option A: allow multiple open
      item.classList.toggle('is-open', !isOpen);

      // If you want ONLY one open at a time, uncomment this block:
      // serviceItems.forEach(i => {
      //   if (i !== item) i.classList.remove('is-open');
      // });
      // item.classList.toggle('is-open', !isOpen);
    });
  });
});
//-- End About Page toggles ----------------



// Mobile nav toggle
document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('[data-nav-toggle]');
  const menu   = document.querySelector('[data-nav-menu]');

  if (!toggle || !menu) return;

  toggle.addEventListener('click', () => {
    const isOpen = menu.classList.toggle('is-open');
    toggle.classList.toggle('is-open', isOpen);
    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
  });

  // Optional: close menu when clicking a link or logout button
  menu.addEventListener('click', (event) => {
    if (event.target.closest('a, button')) {
      menu.classList.remove('is-open');
      toggle.classList.remove('is-open');
      toggle.setAttribute('aria-expanded', 'false');
    }
  });
});
//-- End Mobile nav toggle ----------------



// Navigation bar behavior
document.addEventListener('DOMContentLoaded', () => {
  const navBar  = document.querySelector('.nav-bar');
  const toggle  = document.querySelector('.nav-toggle');
  const menu    = document.querySelector('.nav-groups');

  // Safety: if nav isn't present, stop
  if (!navBar || !toggle || !menu) return;

  // ======= OPEN / CLOSE HELPERS =======
  const openMenu = () => {
    menu.classList.add('is-open');
    toggle.classList.add('is-open');
    toggle.setAttribute('aria-expanded', 'true');
  };

  const closeMenu = () => {
    menu.classList.remove('is-open');
    toggle.classList.remove('is-open');
    toggle.setAttribute('aria-expanded', 'false');
  };

  // Toggle event
  toggle.addEventListener('click', (e) => {
    e.stopPropagation();
    menu.classList.contains('is-open') ? closeMenu() : openMenu();
  });

  // Close when clicking outside
  document.addEventListener('click', (e) => {
    if (!navBar.contains(e.target)) {
      closeMenu();
    }
  });

  // Close menu when clicking a link/button inside it
  menu.addEventListener('click', (e) => {
    if (e.target.closest('a, button')) {
      closeMenu();
    }
  });

  // Shrink nav on scroll
  const handleScroll = () => {
    if (window.scrollY > 10) {
      navBar.classList.add('nav-bar--scrolled');
    } else {
      navBar.classList.remove('nav-bar--scrolled');
    }
    closeMenu(); // collapse menu on scroll
  };

  window.addEventListener('scroll', handleScroll, { passive: true });

  // Run once on load
  handleScroll();
});document.addEventListener('DOMContentLoaded', () => {
  const navBar  = document.querySelector('.nav-bar');
  const toggle  = document.querySelector('.nav-toggle');
  const menu    = document.querySelector('.nav-groups');

  // If nav not present, bail out quietly
  if (!navBar || !toggle || !menu) return;

  const openMenu = () => {
    menu.classList.add('is-open');
    toggle.classList.add('is-open');
    toggle.setAttribute('aria-expanded', 'true');
  };

  const closeMenu = () => {
    menu.classList.remove('is-open');
    toggle.classList.remove('is-open');
    toggle.setAttribute('aria-expanded', 'false');
  };

  // Toggle on hamburger click
  toggle.addEventListener('click', (e) => {
    e.stopPropagation();
    if (menu.classList.contains('is-open')) {
      closeMenu();
    } else {
      openMenu();
    }
  });

  // Close when clicking a link or button inside menu
  menu.addEventListener('click', (e) => {
    if (e.target.closest('a, button')) {
      closeMenu();
    }
  });

  // Close when clicking outside the nav
  document.addEventListener('click', (e) => {
    if (!navBar.contains(e.target)) {
      closeMenu();
    }
  });

  // Shrink nav + close menu on scroll
  const handleScroll = () => {
    if (window.scrollY > 10) {
      navBar.classList.add('nav-bar--scrolled');
    } else {
      navBar.classList.remove('nav-bar--scrolled');
    }
    closeMenu();
  };

  window.addEventListener('scroll', handleScroll, { passive: true });

  // Initial state
  handleScroll();
});

// -- End Navigation bar behavior ----------------

document.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.querySelector('.side');
  const toggle  = document.querySelector('[data-admin-menu-toggle]');
  const overlay = document.querySelector('[data-admin-menu-overlay]');

  if (!sidebar || !toggle || !overlay) return;

  const openDrawer = () => {
    sidebar.classList.add('is-open');
    overlay.classList.add('is-active');
    toggle.classList.add('is-open');
    toggle.setAttribute('aria-expanded', 'true');
  };

  const closeDrawer = () => {
    sidebar.classList.remove('is-open');
    overlay.classList.remove('is-active');
    toggle.classList.remove('is-open');
    toggle.setAttribute('aria-expanded', 'false');
  };

  toggle.addEventListener('click', (e) => {
    e.stopPropagation();
    if (sidebar.classList.contains('is-open')) {
      closeDrawer();
    } else {
      openDrawer();
    }
  });

  overlay.addEventListener('click', closeDrawer);

  // Close if you tap anywhere outside the drawer on mobile
  document.addEventListener('click', (e) => {
    if (window.innerWidth >= 900) return;     // only care on mobile
    if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
      closeDrawer();
    }
  });

  // Close when going back to desktop width
  window.addEventListener('resize', () => {
    if (window.innerWidth >= 900) {
      closeDrawer();
    }
  });

  // Optional: close on Esc
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeDrawer();
  });
});
