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
    if (allDay.checked) {
      timeRow.style.display = 'none';
      startTime.value = '';
      endTime.value = '';
    } else {
      timeRow.style.display = 'flex';
    }
  }

  function syncEndDateDefault() {
    if (startDate.value && !endDate.value) {
      endDate.value = startDate.value;
    }
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

  const typeSelect = form.querySelector('#eventType');
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
    if (typeSelect.value === 'Wake') {
      allDay.checked  = true;
      allDay.disabled = true;
      setDefaultEndDateFromStart(true);
    } else {
      allDay.disabled = false;
      if (!endDate.value && startDate.value) {
        // For normal events, default end date = start date
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

