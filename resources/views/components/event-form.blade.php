<div class="eventForm">
  {{-- DEBUG --}}
<p>POST target should be: {{ route('admin.events.store') }}</p>


  <form action="{{ route('admin.events.store')}}" method="POST" enctype="multipart/form-data" >
    @csrf
    <label for="eventName">Event Name:</label>
    <input type="text" id="eventName" name="eventName" required>

    <label for="eventDate">Event Date:</label>
    <input type="date" id="eventDate" name="eventDate" required>

    <label for="eventTime">Event Time:</label>
    <input type="time" id="eventTime" name="eventTime" required>

    <label>Type</label>
    <select name="eventType">
      <option value="Community">Community</option>
      <option value="Sports">Sports</option>
      <option value="Cultural">Cultural</option>
      <option value="Class">Class</option>
    </select>

    <label for="eventDescription">Event Description:</label>
    <textarea id="eventDescription" name="eventDescription" rows="4" required></textarea>

    <label>Thumbnail Image:</label>
    <input type="file" name="thumbnail_image_path" accept="image/*" >

    <label>Gallery Images (optional):</label>
    <input type="file" name="gallery[]" accept="image/*" multiple>

    <button type="submit">Create Event</button>
  </form>
</div>