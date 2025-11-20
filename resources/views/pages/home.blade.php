<x-app-layout>
  <div class="homeHeroContainer">
    <img src="{{ asset('storage/media/lrcc.jpg') }}" alt="Community Center Hero Image" class="homeHeroImage" loading="lazy">
    <div class="homeHeroTextOverlay">
      <h1 class="homeHeroTitle">Welcome to Our Community Center</h1>
      <p class="homeHeroSubtitle">Bringing People Together</p>  
    </div>
  </div>

  <div class="homeInformationalContainer">
    <div class="infoSection">
      <h2>About Us</h2>
      <p>Learn more about our community center, our mission, and the services we offer to the community.</p>
      <a href="/about" class="btn btn-primary">Learn More</a>
    </div>

    <div class="infoSection">
      <h2>Events</h2>
      <p>Discover upcoming events and activities happening at our community center. Join us and be part of the fun!</p>
      <a href="/events" class="btn btn-primary">View Events</a>
    </div>

    <div class="infoSection">
      <h2>Calendar</h2>
      <p>Check out our calendar to stay updated on all the events, classes, and programs scheduled at the community center.</p>
      <a href="/calendar" class="btn btn-primary">View Calendar</a>
    </div>
  </div>

  <div class="homeEventCarousel">
    <h2 class="carouselHeading">Upcoming Events</h2>
    @if($upcomingEvents->isEmpty())
      <p>No upcoming events at the moment. Please check back later!</p>
    @else
      <div class="carouselContainer">
        @foreach ($upcomingEvents as $event)
          <div class="carouselCard">
            @if ($event->thumbnail_image_path)
              <img
                src="{{ asset('storage/' . $event->thumbnail_image_path) }}"
                alt="{{ $event->title }}"
                class="carouselEventThumbnail"
                loading="lazy"
              >
            @else
              <img
                src="{{ asset('storage/media/nothumbnail.png') }}"
                alt="No thumbnail"
                class="carouselEventThumbnail"
                loading="lazy"
              >
            @endif

            <h3 class="carouselEventTitle">{{ $event->title ?? 'No Title' }}</h3>

            <p class="carouselEventMeta">
              {{ \Carbon\Carbon::parse($event->start_at)->format('F j, Y') }}
              at
              {{ \Carbon\Carbon::parse($event->start_at)->format('g:i A') }}
            </p>

            <a href="{{ route('events.show', $event->slug) }}" class="btn btn-secondary carouselDetailsButton">View Details</a>
          </div>
        @endforeach
      </div>
    @endif
  </div>

  <div class="homeContactFormContainer">
    <h2>Contact Us</h2>
    <x-ui.contact-form />
  </div>
</x-app-layout>