@props(['links' => [], 'showAuthLinks' => true])

<nav {{ $attributes->class(['nav-bar']) }}>
  <ul class="nav-list">
    @foreach ($links as $link)
      <li class="nav-item">
        <x-nav.link
          :href="$link['url']"
          :id="$link['id'] ?? ''"
          :class="$link['class'] ?? ''"
          :active="$link['active'] ?? request()->fullUrlIs($link['url']) || request()->routeIs($link['route'] ?? '')"
        >
          {{ $link['label'] }}
        </x-nav.link>
      </li>
    @endforeach
  </ul>
</nav>
