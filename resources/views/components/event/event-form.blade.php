@props(['event' => null])

  @php
    $today = \Carbon\Carbon::today()->format('Y-m-d');
    $max = \Carbon\Carbon::today()->addYear()->format('Y-m-d');
  @endphp

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


    <label for="eventName">Event Name:</label>
    <input 
      type="text" 
      id="eventName" 
      name="title" 
      value="{{ old('title', $event->title ?? '') }}" 
      required
    >

    <label for="eventDate">Start Date:</label>
    <input
      type="date"
      id="eventDate"
      name="start_date"
      value="{{ old('start_date', $event->start_date ?? '') }}"
      @if(!isset($event))
        min="{{ $today }}" max="{{ $max }}"
      @endif
      required
    >

    <label for="endDate">End Date (optional):</label>
    <input
      type="date"
      id="endDate"
      name="end_date"
      value="{{ old('end_date', optional($event?->end_at)->format('Y-m-d')) }}"
      @if(!isset($event))
        min="{{ $today }}" max="{{ $max }}"
      @endif
    >


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
      <div class="time-row">
        <div class="time-field">
          <x-ui.input-label for="eventTime">Start Time:</x-ui.input-label>
          <select id="eventTime" name="eventTime">
            <option value="">Select a time...</option>
            @for ($h = 6; $h <= 22; $h++)
              <option value="{{ sprintf('%02d:00', $h) }}">{{ date('g:i A', mktime($h, 0)) }}</option>
              <option value="{{ sprintf('%02d:30', $h) }}">{{ date('g:i A', mktime($h, 30)) }}</option>
            @endfor
          </select>
        </div>

        <div class="time-field">
          <x-ui.input-label for="endTime">End Time (optional):</x-ui.input-label>
          <select id="endTime" name="endTime">
            <option value="">Select a time...</option>
            @for ($h = 6; $h <= 22; $h++)
              <option value="{{ sprintf('%02d:00', $h) }}">{{ date('g:i A', mktime($h, 0)) }}</option>
              <option value="{{ sprintf('%02d:30', $h) }}">{{ date('g:i A', mktime($h, 30)) }}</option>
            @endfor
          </select>
        </div>
      </div>

    {{-- Type --}}
    <label for="event_type">Type</label>
    <select name="event_type" id="event_type">
      @foreach(['Community Event', 'Class/Program', 'Sports', 'Rental', 'Facility Closure', 'Notice', 'Wake'] as $type)
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

    <div class="form-actions">
      <button type="submit" class="btn-primary">{{ isset($event) ? 'Update Event' : 'Create Event' }}</button>

      @if (isset($event))
        <button type="button" class="btn-danger" onclick="document.getElementById('delete-event-form').submit()">Delete Event</button>
      @endif
    </div>
  </form>
</div>
@if (isset($event))
  <form id="delete-event-form" action="{{ route('admin.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this event? This cannot be undone.');">
    @csrf
    @method('DELETE')
  </form>
@endif

</div>
