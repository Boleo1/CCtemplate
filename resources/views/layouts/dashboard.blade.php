@props(['title' => null])

<x-app-layout :dashboard="true">
  <div class="admin-shell">
    {{-- Slide-in drawer sidebar --}}
    <x-dashboard.sidebar />

    {{-- Dark overlay for mobile drawer --}}
    <div class="admin-overlay" data-admin-menu-overlay></div>

    {{-- Main content --}}
    <main class="admin-main">
      {{-- Mobile-only dashboard topbar --}}
      <header class="admin-topbar">

        <button
          type="button"
          class="admin-menu-toggle"
          aria-label="Open dashboard menu"
          aria-expanded="false"
          data-admin-menu-toggle
        >
          <span></span>
          <span></span>
          <span></span>
        </button>
      </header>

      {{ $slot }}
    </main>
  </div>
</x-app-layout>
