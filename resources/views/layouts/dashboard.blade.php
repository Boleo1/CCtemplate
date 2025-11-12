@props([
  'title' => null,
])

<x-app-layout>
  <x-header />
  
  <div class="admin-shell">
    <x-dashboard.sidebar />
    <main class="admin-main">
        {{ $slot }}
    </main>
  </div>
</x-app-layout>
