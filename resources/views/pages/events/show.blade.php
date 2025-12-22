<x-app-layout>
  <div class="event-show">

    @auth
      <a href="{{ route('admin.events.edit', $event->id) }}"
         class="btn btn-secondary admin-edit-btn">
        Edit Event
      </a>
    @endauth

    <h1 class="event-title">{{ $event->title }}</h1>

    {{-- Meta pills --}}
    <div class="event-meta">
      <span class="meta">📅 {{ $event->start_at->format('F j, Y') }}</span>

      @unless($event->all_day)
        <span class="meta">⌚ {{ $event->start_at->format('g:i A') }}</span>
      @endunless

      <span class="meta">🏷️ {{ $event->event_type }}</span>
    </div>

    {{-- Description --}}
    <div class="event-body">
      {!! nl2br(e($event->description)) !!}
    </div>

    {{-- Thumbnail (subtle + lightbox) --}}
    @if ($event->thumbnail_image_path)
      <a href="{{ asset('storage/'.$event->thumbnail_image_path) }}" class="lightbox-trigger">
        <img
          src="{{ asset('storage/'.$event->thumbnail_image_path) }}"
          alt="{{ $event->title }}"
          class="event-thumbnail"
          loading="lazy"
        >
      </a>
    @endif



    {{-- Gallery --}}
    @if ($event->galleryImages->count())
      <h2 class="gallery-title">Gallery</h2>

      <div class="gallery">
        @foreach($event->galleryImages as $img)
          <a
            href="{{ asset('storage/' . $img->image_path) }}"
            class="lightbox-trigger"
          >
            <img
              src="{{ asset('storage/' . $img->image_path) }}"
              alt="Gallery image"
              loading="lazy"
            >
          </a>
        @endforeach
      </div>
    @endif
    <div id="lightbox" class="lightbox" hidden>
      <img id="lightbox-img" alt="">
    </div>


  </div>
</x-app-layout>
