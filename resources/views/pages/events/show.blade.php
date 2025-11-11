<x-app-layout>
  <x-header><h1>{{ $event->title }}</h1></x-header>
  
  @if(Auth::check())
    <a href={{ route('admin.events.edit', $event->id) }} class="btn btn-secondary admin-edit-btn">Edit Event</a>
  @endif
  @if ($event->thumbnail_image_path)
    <img src="{{ asset('storage/'.$event->thumbnail_image_path) }}" alt="{{ $event->title }}" class="event-hero">
  @endif

  <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($event->start_at)->format('F j, Y') }}</p>
  <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($event->start_at)->format('g:i A') }}</p>
  <p><strong>Type:</strong> {{ $event->event_type }}</p>

  <div class="event-body">
    {!! nl2br(e($event->description)) !!}
  </div>

  @if($event->galleryImages->count())
    <h2>Gallery</h2>
    <div class="gallery">
      @foreach($event->galleryImages as $img)
        <img src="{{ asset('storage/' . $img->image_path) }}" alt="Gallery image" loading="lazy">
      @endforeach
    </div>
  @endif


</x-app-layout>

