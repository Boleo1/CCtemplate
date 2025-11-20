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
    if (allDay.checked || typeSelect.value === 'Wake') {
      timeRow.style.display = 'none';
      startTime.value = '';
      endTime.value   = '';
    } else {
      timeRow.style.display = 'flex';
    }
  }

  function setDefaultEndDateFromStart(addDaysIfWake = false) {
    if (!startDate.value) return;

    const base = new Date(startDate.value);
    if (typeSelect.value === 'Wake' && addDaysIfWake) {
      // Start date + 2 days for wake
      base.setDate(base.getDate() + 2);
    }

    const iso = base.toISOString().slice(0,10);

    // Only update if empty or earlier than suggested
    if (!endDate.value || endDate.value < iso) {
      endDate.value = iso;
    }
  }

  function updateWakeLogic() {
    if (!typeSelect || !allDay) return;

    if (typeSelect.value === 'Wake') {
      allDay.checked  = true;
      // allDay.disabled = true;  <-- REMOVE this line
      setDefaultEndDateFromStart(true);
    } else {
      allDay.disabled = false;  // you can also drop this if you’re not disabling
      if (endDate && startDate && !endDate.value && startDate.value) {
        endDate.value = startDate.value;
      }
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
      if (typeSelect.value === 'Wake') {
        setDefaultEndDateFromStart(true);
      } else {
        if (!endDate.value) {
          endDate.value = startDate.value;
        }
      }
    });
  }

  // Init on load
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
