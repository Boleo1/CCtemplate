<x-app-layout>
  <x-header />

  <section class="contact-hero">
    <h1>Contact Us</h1>
    <p>We’re here to help with questions, event inquiries, room rentals, and more.</p>
  </section>

  <section class="contact-wrapper">

    {{-- Left Column: Contact Info --}}
    <div class="contact-info">
      <h2>Get In Touch</h2>

      <div class="info-block">
        <h3>Phone</h3>
        <p>(218) 679-3594</p>
      </div>

      <div class="info-block">
        <h3>Email</h3>
        <p>info@communitycenter.com</p>
      </div>

      <div class="info-block">
        <h3>Address</h3>
        <p>28017 State Hwy 1<br>Red Lake, MN 56671</p>

        <div class="map-container">
          <iframe
  src="https://maps.google.com/maps?q=Red%20Lake%20MN&t=&z=13&ie=UTF8&iwloc=&output=embed"
  width="100%" height="200">
</iframe>

        </div>
      </div>

      <div class="info-block">
        <h3>Hours</h3>
        <ul class="hours-list">
          <li>Mon–Fri: 8 AM – 6 PM</li>
          <li>Saturday: 10 AM – 4 PM</li>
          <li>Sunday: Closed</li>
        </ul>
      </div>

      <div class="info-block">
        <h3>Accessibility</h3>
        <p>We provide wheelchair-accessible entrances and accommodations upon request.</p>
      </div>
    </div>

    {{-- Right Column: Contact Form --}}
    <div class="contact-form-container">
      <h2>Send Us a Message</h2>

      <x-ui.contact-form />
    </div>

  </section>

  {{-- Quick Links --}}
  <section class="contact-quicklinks">
    <h2>Quick Assistance</h2>
    <p>You may find what you're looking for here:</p>

    <div class="quicklinks-grid">
      <a href="/events" class="quicklink-card">Upcoming Events</a>
      <a href="/calendar" class="quicklink-card">Event Calendar</a>
      <a href="/request" class="quicklink-card">Request an Event</a>
      <a href="/about" class="quicklink-card">About the Center</a>
    </div>
  </section>

</x-app-layout>
