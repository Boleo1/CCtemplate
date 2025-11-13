@props(['event' => null])

<div class="eventForm">
  <form 
    action="{{ isset($event) ? route('admin.events.update', $event->id) : route('admin.events.store') }}" 
    method="POST" 
    enctype="multipart/form-data"
    id="admin-event-form"
  >
    @csrf
    @if(isset($event))
      @method('PATCH')
    @endif

    {{-- Title --}}
    <label for="eventName">Event Name:</label>
    <input 
      type="text" 
      id="eventName" 
      name="title" 
      value="{{ old('title', $event->title ?? '') }}" 
      required
    >

    {{-- Start / End dates --}}
    <label for="eventDate">Start Date:</label>
    <input 
      type="date" 
      id="eventDate" 
      name="start_date" 
      value="{{ old('start_date', $event->start_date ?? '') }}" 
      required
    >

    <label for="endDate">End Date (optional):</label>
    <input 
      type="date" 
      id="endDate" 
      name="end_date" 
      value="{{ old('end_date', $event->end_date ?? '') }}"
    >

    {{-- All-day --}}
    <div class="checkbox-inline">
      <input
        type="checkbox"
        id="all_day"
        name="all_day"
        value="1"
        {{ old('all_day', $event->all_day ?? false) ? 'checked' : '' }}
      >
      <label for="all_day">All-day event</label>
    </div>

    {{-- Time fields --}}
    <div class="timeRow">
      <div class="timeField">
        <label for="eventTime">Start Time:</label>
        <input 
          type="time" 
          id="eventTime" 
          name="start_time" 
          value="{{ old('start_time', $event->start_time ?? '') }}"
        >
      </div>

      <div class="timeField">
        <label for="endTime">End Time (optional):</label>
        <input 
          type="time" 
          id="endTime" 
          name="end_time" 
          value="{{ old('end_time', $event->end_time ?? '') }}"
        >
      </div>
    </div>

    {{-- Type --}}
    <label for="event_type">Type</label>
    <select name="event_type" id="event_type">
      @foreach(['Community', 'Sports', 'Cultural', 'Class'] as $type)
        <option value="{{ $type }}" 
          {{ old('event_type', $event->event_type ?? '') === $type ? 'selected' : '' }}>
          {{ $type }}
        </option>
      @endforeach
    </select>

    {{-- Description --}}
    <label for="eventDescription">Event Description:</label>
    <textarea 
      id="eventDescription" 
      name="description" 
      rows="4" 
      required
    >{{ old('description', $event->description ?? '') }}</textarea>

    <hr>
    
    {{-- Thumbnail --}}
    <label>Thumbnail Image:</label>
    <input type="file" name="thumbnail_image_path" accept="image/*">

    @if(isset($event) && $event->thumbnail_image_path)
      <img src="{{ asset('storage/'.$event->thumbnail_image_path) }}" alt="Current Thumbnail" class="current-thumb">
    @endif

    {{-- Gallery --}}
    <label>Gallery Images (optional):</label>
    <input type="file" name="gallery[]" accept="image/*" multiple>

    <button type="submit">
      {{ isset($event) ? 'Update Event' : 'Create Event' }}
    </button>
  </form>
</div>
