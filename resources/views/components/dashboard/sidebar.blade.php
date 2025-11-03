<aside class="side">
  <div class="side-section">
    <div class="side-title">Manage</div>
    <x-nav.link :href="route('home')" :active="request()->routeIs('home')">Home</x-nav.link>
    <x-nav.link :href="route('admin.index')" :active="request()->routeIs('admin.index')">Overview</x-nav.link>
    <x-nav.link :href="route('admin.events.index')" :active="request()->routeIs('admin.events.*')">Events</x-nav.link>
    <x-nav.link :href="route('admin.requests.index')" :active="request()->routeIs('admin.requests.index')">Requests</x-nav.link>
  </div>

  <div class="side-section">
    <div class="side-title">Utilities</div>
    {{-- Add more links or tools here --}}
  </div>
</aside>
