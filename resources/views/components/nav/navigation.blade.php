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

  @if (Auth::check() && $showAuthLinks)
    <ul class="nav-list nav-auth">
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
    </ul>
    @else
    <ul class="nav-list nav-auth">
      <li class="nav-item">
        <x-nav.link
          href="{{ route('login') }}"
          :active="request()->routeIs('login')"
        >
          Login
        </x-nav.link>
      </li>
  @endif
</nav>
