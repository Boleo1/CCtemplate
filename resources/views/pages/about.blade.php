<x-app-layout>
  <section class="aboutHero">
    <img src="{{ asset('storage/media/lrcc2.jpg') }}" alt="About Us Hero Image" class="aboutHeroImage" loading="lazy">
    <div class="aboutHeroContent">
      <h1>About Our Community Center</h1>
      <p>Connecting People, Enriching Lives</p>
    </div>
  </section>

  <section class="aboutMission">
    <div class="aboutMissionContent">
      <h2>Our Mission</h2>
      <p>At Our Community Center, our mission is to foster a welcoming environment where individuals of all ages and backgrounds can come together to learn, grow, and connect. We are dedicated to providing diverse programs and services that enhance the well-being of our community members.</p>

      <h2>Our Vision</h2>
      <p>We envision a community where everyone has access to meaningful experiences, lifelong learning, and a sense of belonging.</p>
    </div>
  </section>

  <section class="aboutServices">
  <div class="aboutServicesContent">
    <h2>What We Offer</h2>
    <p class="servicesIntro">
      Our community center provides programs and services for youth, families, and elders.
      Tap a category below to learn more about what we offer.
    </p>

    <ul class="servicesList">
      <li class="service-item">
        <button type="button" class="service-pill">
          Educational Workshops and Classes
          <span class="service-pill-icon">▾</span>
        </button>
        <div class="service-panel">
          <p>
            Hands-on learning opportunities in arts, crafts, culture, language, and basic
            technology. Great for youth, adults, and elders who want to keep learning.
          </p>
        </div>
      </li>

      <li class="service-item">
        <button type="button" class="service-pill">
          Recreational Activities and Sports
          <span class="service-pill-icon">▾</span>
        </button>
        <div class="service-panel">
          <p>
            Open gym times, youth sports, fitness activities, and seasonal leagues that
            encourage healthy lifestyles and friendly competition.
          </p>
        </div>
      </li>

      <li class="service-item">
        <button type="button" class="service-pill">
          Community Events and Gatherings
          <span class="service-pill-icon">▾</span>
        </button>
        <div class="service-panel">
          <p>
            Dances, family nights, holiday events, cultural celebrations, and community
            gatherings that bring people together throughout the year.
          </p>
        </div>
      </li>

      <li class="service-item">
        <button type="button" class="service-pill">
          Support Services and Resources
          <span class="service-pill-icon">▾</span>
        </button>
        <div class="service-panel">
          <p>
            Access to information, referrals, and partnerships that connect community members
            with wellness, housing, education, and support resources.
          </p>
        </div>
      </li>

      <li class="service-item">
        <button type="button" class="service-pill">
          Volunteer Opportunities
          <span class="service-pill-icon">▾</span>
        </button>
        <div class="service-panel">
          <p>
            Ways to give back — from helping at events to mentoring youth and supporting
            day-to-day operations at the center.
          </p>
        </div>
      </li>
    </ul>
  </div>
</section>


<section class="aboutTeam">
  <div class="aboutTeamContent">
    <h2>Meet Our Team</h2>
    <p>Our dedicated staff and volunteers help create a welcoming, supportive, and active community environment.</p>
  </div>

  <div class="teamGrid">

    {{-- Demo Team Member --}}
    <div class="teamCard">
      <img 
        src="{{ asset('storage/media/team1.jpg') }}" 
        onerror="this.onerror=null;this.src='{{ asset('storage/media/portraitplaceholder.png') }}';"
        alt="Team Member"
        class="teamImage"
      >
      <h3>Jane Doe</h3>
      <p class="role">Director</p>
      <p class="bio">Oversees operations and works closely with staff to develop programs that serve the community.</p>
    </div>

    {{-- Demo Team Member --}}
    <div class="teamCard">
      <img 
        src="{{ asset('storage/media/team2.jpg') }}" 
        onerror="this.onerror=null;this.src='{{ asset('storage/media/portraitplaceholder.png') }}';"
        alt="Team Member"
        class="teamImage"
      >
      <h3>Michael Running</h3>
      <p class="role">Youth Coordinator</p>
      <p class="bio">Leads youth activities, after-school programs, and community engagement projects.</p>
    </div>

    {{-- Demo Team Member --}}
    <div class="teamCard">
      <img 
        src="{{ asset('storage/media/team3.jpg') }}" 
        onerror="this.onerror=null;this.src='{{ asset('storage/media/portraitplaceholder.png') }}';"
        alt="Team Member"
        class="teamImage"
      >
      <h3>Sarah O’Malley</h3>
      <p class="role">Event Manager</p>
      <p class="bio">Organizes facility rentals, community events, and assists residents looking to host gatherings.</p>
    </div>

  </div>

</section>


  <section class="aboutCTA">
    <div class="aboutCTA-inner">
      <h2>Get Involved</h2>
      <p>Want to host an event, volunteer, or join a program? We'd love to have you involved in making our community even stronger.</p>
      <a href="/contact" class="btn btn-primary">Contact Us</a>
    </div>
  </section>


</x-app-layout>