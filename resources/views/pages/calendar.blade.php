<x-app-layout>
  <x-header>
    <h1>Calendar</h1>
  
  </x-header>
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


<div id="form-container" >
  <form class="calendar-form" id="calendar-form" action="{{ route('events.request.submit') }}" method="POST" >
    @csrf
    <label for="eventType">Event Type:</label>
      <select name="eventType" id="eventType" required>
        <option value="Activity">Activity</option>
        <option value="Sports">Sports</option>
        <option value="Cultural">Cultural</option>
        <option value="Class">Class</option>
        <option value="Sale">Sale</option>
        <option value="Wake">Wake</option>
      </select>
    <label for="date">Event Date:</label>
    <input type="date" id="date" name="date" required>

    <label for="eventTime">Event Time:</label>
    <input step="1800" type="time" id="eventTime" name="eventTime" required>

    <label for="requesterEmail">Your E-Mail:</label>
    <input type="email" id="requesterEmail" name="requesterEmail" required>
    
    <label for="eventDescription">Additional Details:</label>
    <textarea id="eventDescription" name="eventDescription" rows="4" required></textarea>
    
    <x-ui.button class="btn-primary" type="submit">Submit</x-ui.button>
    <x-ui.button class="btn-secondary" type="button" id="nav-form-cancel-btn">Cancel</x-ui.button>
  </form>
</div>

</x-app-layout>