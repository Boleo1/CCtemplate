<x-app-layout>
<x-header>
  <h1>Events</h1>
</x-header>
  <p> Events page where I will display events that are created from admin dashboard.</p>

  <button id="openEventFormButton">Create Event</button>
  <button id="closeEventFormButton">Close Form</button>
  <div class="eventPageContainer">
    <div class="eventForm">
      <form action="">
        <label for="eventName">Event Name:</label>
        <input type="text" id="eventName" name="eventName" required>

        <label for="requestedBy">Enter your E-Mail:</label>
        <input type="text" id="requestedBy" name="requestedBy" required>

        <label for="eventDate">Event Date:</label>
        <input type="date" id="eventDate" name="eventDate" required>

        <label for="eventTime">Event Time:</label>
        <input type="time" id="eventTime" name="eventTime" required>

        <label>Type</label>
        <select name="type">
          <option value="Community">Community</option>
          <option value="Sports">Sports</option>
          <option value="Cultural">Cultural</option>
          <option value="Class">Class</option>
        </select>

        <label for="eventDescription">Event Description:</label>
        <textarea id="eventDescription" name="eventDescription" rows="4" required></textarea>

        <button type="submit">Create Event</button>
      </form>

  </div>
</x-app-layout>