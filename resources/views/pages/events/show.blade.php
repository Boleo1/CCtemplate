<x-app-layout>
  <div class="event-show">

    @if(Auth::check())
      <a href="{{ route('admin.events.edit', $event->id) }}"
         class="btn btn-secondary admin-edit-btn">Edit Event</a>
    @endif

    <h1 class="event-title">{{ $event->title }}</h1>

    <div class="event-meta">
      <span class="meta">📅 {{ \Carbon\Carbon::parse($event->start_at)->format('F j, Y') }}</span>

      @unless($event->all_day)
        <span class="meta">⌚ {{ \Carbon\Carbon::parse($event->start_at)->format('g:i A') }}</span>
      @endunless

      <span class="meta">🏷️ {{ $event->event_type }}</span>
    </div>

    @if ($event->thumbnail_image_path)
      <a href="{{ asset('storage/'.$event->thumbnail_image_path) }}" target="_blank">
        <img
          src="{{ asset('storage/'.$event->thumbnail_image_path) }}"
          alt="{{ $event->title }}"
          class="event-thumbnail"
          loading="lazy"
        >
      </a>
    @endif

    <div class="event-body">
      {!! nl2br(e($event->description)) !!}
    </div>

    @if($event->galleryImages->count())
      <h2 class="gallery-title">Gallery</h2>
      <div class="gallery">
        @foreach($event->galleryImages as $img)
          <a href="{{ asset('storage/' . $img->image_path) }}" target="_blank">
            <img src="{{ asset('storage/' . $img->image_path) }}" alt="Gallery image" loading="lazy">
          </a>
        @endforeach
      </div>
    @endif

  </div>
</x-app-layout>
