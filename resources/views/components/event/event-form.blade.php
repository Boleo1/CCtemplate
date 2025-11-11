@props(['event' => null])

<div class="eventForm">
  <form 
    action="{{ isset($event) ? route('admin.events.update', $event->id) : route('admin.events.store') }}" 
    method="POST" 
    enctype="multipart/form-data"
  >
    @csrf
    @if(isset($event))
      @method('PATCH')
    @endif

    <label for="eventName">Event Name:</label>
    <input 
      type="text" 
      id="eventName" 
      name="title" 
      value="{{ old('title', $event->title ?? '') }}" 
      required
    >

    <label for="eventDate">Event Date:</label>
    <input 
      type="date" 
      id="eventDate" 
      name="start_date" 
      value="{{ old('start_date', $event->start_date ?? '') }}" 
      required
    >

    <label for="eventTime">Event Time:</label>
    <input 
      type="time" 
      id="eventTime" 
      name="start_time" 
      value="{{ old('start_time', $event->start_time ?? '') }}" 
      required
    >

    <label>Type</label>
    <select name="event_type">
      @foreach(['Community', 'Sports', 'Cultural', 'Class'] as $type)
        <option value="{{ $type }}" 
          {{ old('event_type', $event->event_type ?? '') === $type ? 'selected' : '' }}>
          {{ $type }}
        </option>
      @endforeach
    </select>

    <label for="eventDescription">Event Description:</label>
    <textarea 
      id="eventDescription" 
      name="description" 
      rows="4" 
      required
    >{{ old('description', $event->description ?? '') }}</textarea>

    <label>Thumbnail Image:</label>
    <input type="file" name="thumbnail_image_path" accept="image/*">

    @if(isset($event) && $event->thumbnail_image_path)
      <img src="{{ asset('storage/'.$event->thumbnail_image_path) }}" alt="Current Thumbnail" class="current-thumb">
    @endif

    <label>Gallery Images (optional):</label>
    <input type="file" name="gallery[]" accept="image/*" multiple>

    <button type="submit">
      {{ isset($event) ? 'Update Event' : 'Create Event' }}
    </button>
  </form>
</div>
