import './bootstrap';

// ============================================
// Time duration syncing, Dashboard Event Form
// ============================================

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('admin-event-form');
  if (!form) return;

  const allDay    = form.querySelector('#all_day');
  const timeRow   = form.querySelector('.time-row');
  const startTime = form.querySelector('#eventTime');
  const endTime   = form.querySelector('#endTime');
  const startDate = form.querySelector('#eventDate');
  const endDate   = form.querySelector('#endDate');

  const DEFAULT_DURATION_MIN = 240;
  let durationMinutes = null;

  // ---------------------------
  // ✅ End date clamp (max 5 days)
  // ---------------------------
  const MAX_SPAN_DAYS = 5;

  function addDaysISO(isoDate, days) {
    const d = new Date(isoDate + 'T00:00:00');
    d.setDate(d.getDate() + days);
    return d.toISOString().slice(0, 10);
  }

  function syncEndDateRules() {
    if (!startDate || !endDate || !startDate.value) return;

    const min = startDate.value;
    const max = addDaysISO(min, MAX_SPAN_DAYS);

    endDate.min = min;
    endDate.max = max;

    // default to start date if empty
    if (!endDate.value) {
      endDate.value = min;
      return;
    }

    // hard clamp if out of range
    if (endDate.value < min) endDate.value = min;
    if (endDate.value > max) endDate.value = max;
  }

  // ---------------------------
  // Time helpers (your original)
  // ---------------------------
  function timeToMinutes(timeStr) {
    if (!timeStr) return null;
    const [h, m] = timeStr.split(':').map(Number);
    return h * 60 + (m || 0);
  }

  function minutesToTime(totalMinutes) {
    if (totalMinutes == null) return '';
    if (totalMinutes < 0) totalMinutes = 0;
    if (totalMinutes >= 24 * 60) totalMinutes = 24 * 60 - 1;

    const h = Math.floor(totalMinutes / 60);
    const m = totalMinutes % 60;
    return `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}`;
  }

  function updateTimeRowVisibility() {
    if (!allDay || !timeRow || !startTime || !endTime) return;

    if (allDay.checked) {
      timeRow.style.display = 'none';
      startTime.value = '';
      endTime.value = '';
      durationMinutes = null;
    } else {
      timeRow.style.display = 'flex';
    }
  }

  function handleStartTimeChange() {
    if (!startTime || !endTime || !startTime.value) return;

    const startMin = timeToMinutes(startTime.value);
    if (startMin == null) return;

    if (durationMinutes == null) {
      if (endTime.value) {
        const endMin = timeToMinutes(endTime.value);
        durationMinutes = (endMin != null && endMin > startMin)
          ? (endMin - startMin)
          : DEFAULT_DURATION_MIN;
      } else {
        durationMinutes = DEFAULT_DURATION_MIN;
      }
    }

    endTime.value = minutesToTime(startMin + durationMinutes);
  }

  function handleEndTimeChange() {
    if (!startTime || !endTime || !startTime.value || !endTime.value) return;

    const startMin = timeToMinutes(startTime.value);
    const endMin   = timeToMinutes(endTime.value);
    if (startMin == null || endMin == null) return;

    const diff = endMin - startMin;
    durationMinutes = diff > 0 ? diff : DEFAULT_DURATION_MIN;
  }

  // ---------------------------
  // Listeners
  // ---------------------------
  startTime?.addEventListener('change', handleStartTimeChange);
  startTime?.addEventListener('input', handleStartTimeChange);

  endTime?.addEventListener('change', handleEndTimeChange);
  endTime?.addEventListener('input', handleEndTimeChange);

  allDay?.addEventListener('change', updateTimeRowVisibility);

  startDate?.addEventListener('change', syncEndDateRules);
  endDate?.addEventListener('change', syncEndDateRules);
  endDate?.addEventListener('input', syncEndDateRules);

  // Init
  updateTimeRowVisibility();
  syncEndDateRules();
});


// ============================================
// Time duration syncing, Dashboard Event Form END
// ============================================



// ======================================
// Time duration syncing, Calendar Form
// ======================================

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('calendar-form');
  if (!form) return;

  const typeSelect = form.querySelector('#event_type');
  const startDate  = form.querySelector('#date');
  const endDate    = form.querySelector('#endDate');
  const allDay     = form.querySelector('#allDay');
  const timeRow    = form.querySelector('.time-row');
  const startTime  = form.querySelector('#eventTime');
  const endTime    = form.querySelector('#endTime');

  // ---- Settings ----
  const DEFAULT_DURATION_MIN = 4 * 60; // 4 hours

  let durationMin = DEFAULT_DURATION_MIN;
  let userHasSetEnd = false;

  // ---- Helpers ----
  const toMin = (hhmm) => {
    if (!hhmm) return null;
    const [h, m] = hhmm.split(':').map(Number);
    return h * 60 + (m || 0);
  };

  const toHHMM = (mins) => {
    mins = ((mins % 1440) + 1440) % 1440;
    const h = String(Math.floor(mins / 60)).padStart(2, '0');
    const m = String(mins % 60).padStart(2, '0');
    return `${h}:${m}`;
  };

  const addDaysISO = (isoDate, days) => {
    const d = new Date(isoDate + 'T00:00:00');
    d.setDate(d.getDate() + days);
    return d.toISOString().slice(0, 10);
  };

  const clampToSelectOptions = (selectEl, hhmm) => {
    if (!selectEl) return hhmm;
    const hasOption = Array.from(selectEl.options).some(o => o.value === hhmm);
    if (hasOption) return hhmm;

    const target = toMin(hhmm);
    let best = null;
    for (const opt of selectEl.options) {
      const v = opt.value;
      if (!v) continue;
      const m = toMin(v);
      if (m != null && m <= target) best = v;
    }
    return best ?? selectEl.options[selectEl.options.length - 1]?.value ?? '';
  };

  const isWake = () => typeSelect?.value === 'Wake';

  const inTimeMode = () => {
    if (!timeRow || !startTime || !endTime) return false;
    if (allDay?.checked) return false;
    if (isWake()) return false;
    return timeRow.style.display !== 'none';
  };

  // ---- Time behavior ----
  const recomputeDurationFromInputs = () => {
    const s = toMin(startTime.value);
    const e = toMin(endTime.value);
    if (s == null || e == null) return;

    let diff = e - s;
    if (diff <= 0) diff += 1440;                 // allow crossing midnight
    diff = Math.max(30, Math.min(diff, 12 * 60)); // guardrails
    durationMin = diff;
  };

  const pushEndFromStart = () => {
    if (!inTimeMode()) return;
    const s = toMin(startTime.value);
    if (s == null) return;

    if (!endTime.value && !userHasSetEnd) durationMin = DEFAULT_DURATION_MIN;
    const computed = toHHMM(s + durationMin);
    endTime.value = clampToSelectOptions(endTime, computed);
  };

  function updateTimeRowVisibility() {
    if (!timeRow) return;

    if (allDay?.checked || isWake()) {
      timeRow.style.display = 'none';
      if (startTime) startTime.value = '';
      if (endTime)   endTime.value   = '';
      userHasSetEnd = false;
      durationMin = DEFAULT_DURATION_MIN;
    } else {
      timeRow.style.display = 'flex';
      if (startTime?.value && !endTime?.value) pushEndFromStart();
    }
  }

  // ---- Date behavior (locked end date rules) ----
  function syncEndDateRules() {
    if (!startDate || !endDate || !startDate.value) return;

    const startISO = startDate.value;

    // Always lock endDate (no sprawling events)
    endDate.disabled = true;

    if (isWake()) {
      // Wake: ALWAYS 2 days (start + 2)
      const wakeEnd = addDaysISO(startISO, 2);
      endDate.min = wakeEnd;
      endDate.max = wakeEnd;
      endDate.value = wakeEnd;
    } else {
      // Non-wake: end date equals start date
      endDate.min = startISO;
      endDate.max = startISO;
      endDate.value = startISO;
    }
  }

  function updateWakeLogic() {
    if (!typeSelect || !allDay) return;

    if (isWake()) {
      allDay.checked = true; // wakes default to all-day
    }

    syncEndDateRules();
    updateTimeRowVisibility();
  }

  // ---- Listeners ----
  startTime?.addEventListener('change', () => { if (inTimeMode()) pushEndFromStart(); });

  endTime?.addEventListener('change', () => {
    if (!inTimeMode()) return;
    userHasSetEnd = !!endTime.value;
    if (endTime.value) recomputeDurationFromInputs();
  });

  allDay?.addEventListener('change', () => {
    updateTimeRowVisibility();
    if (!allDay.checked) pushEndFromStart();
  });

  typeSelect?.addEventListener('change', updateWakeLogic);

  startDate?.addEventListener('change', () => {
    syncEndDateRules();
  });

  // Init
  if (startTime?.value && endTime?.value) {
    userHasSetEnd = true;
    recomputeDurationFromInputs();
  }

  updateWakeLogic();
});

// ======================================
// Time duration syncing, Calendar Form END
// ======================================




// ==========================
// Flash message auto-hide
// ==========================

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

// ==========================
// Flash message auto-hide END
// ==========================
 


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

      // ONLY one open at a time, uncomment this block:
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

// Admin sidebar toggle
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
// -- End Admin sidebar toggle ----------------


// Lightbox (safe + reusable)
document.addEventListener('click', function (e) {
  const trigger = e.target.closest('.lightbox-trigger');
  if (!trigger) return;

  const lightbox = document.getElementById('lightbox');
  const img = document.getElementById('lightbox-img');

  // If lightbox markup isn't present on this page, allow normal navigation
  if (!lightbox || !img) return;

  e.preventDefault();

  img.src = trigger.href;
  lightbox.hidden = false;
});

// Close on click
document.addEventListener('click', function (e) {
  const lightbox = document.getElementById('lightbox');
  const img = document.getElementById('lightbox-img');
  if (!lightbox || !img) return;

  if (e.target === lightbox || e.target === img) {
    lightbox.hidden = true;
    img.src = '';
  }
});

// Optional: close on ESC
document.addEventListener('keydown', function (e) {
  if (e.key !== 'Escape') return;

  const lightbox = document.getElementById('lightbox');
  const img = document.getElementById('lightbox-img');
  if (!lightbox || !img) return;

  lightbox.hidden = true;
  img.src = '';
});
