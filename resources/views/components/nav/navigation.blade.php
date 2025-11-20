@props(['links' => [], 'showAuthLinks' => true])

<nav {{ $attributes->class(['nav-bar']) }}>
  {{-- Brand / site title --}}
  <div class="nav-brand">
    <a href="{{ url('/') }}" class="nav-brand-link">
      Community Center
    </a>
  </div>

  {{-- Hamburger (shown on mobile) --}}
  <button
    class="nav-toggle"
    type="button"
    aria-label="Toggle navigation"
    aria-expanded="false"
    data-nav-toggle
  >
    <span class="nav-toggle-bar"></span>
    <span class="nav-toggle-bar"></span>
    <span class="nav-toggle-bar"></span>
  </button>

  {{-- Main nav groups --}}
  <div class="nav-groups" data-nav-menu>
    <ul class="nav-list nav-primary">
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

    @if ($showAuthLinks)
      <ul class="nav-list nav-auth">
        @auth
          <li class="nav-item">
            <x-nav.link
              href="{{ route('admin.index', absolute: false) ?? url('/dashboard') }}"
              :active="request()->routeIs('admin.*','dashboard.*') || request()->is('dashboard','dashboard/*')"
            >
              Dashboard
            </x-nav.link>
          </li>
          <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
              @csrf
              <button type="submit" class="nav-linklike">Logout</button>
            </form>
          </li>
        @else
          <li class="nav-item">
            <x-nav.link
              href="{{ route('login') }}"
              :active="request()->routeIs('login')"
            >
              Login
            </x-nav.link>
          </li>
        @endauth
      </ul>
    @endif
  </div>
</nav>
