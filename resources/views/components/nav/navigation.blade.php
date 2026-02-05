@props(['links' => [], 'showAuthLinks' => true])

<nav {{ $attributes->class(['nav-bar']) }}>
  {{-- Brand / site title --}}
  <div class="nav-brand">
    <a href="{{ url('/') }}" class="nav-brand-link">
      Community Center
    </a>
  </div>

  {{-- Hamburger for mobile --}}
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
      @php
        $hasChildren = !empty($link['children']) && is_array($link['children']);

        $parentUrl  = $link['url'] ?? '';
        $parentPath = trim(parse_url($parentUrl, PHP_URL_PATH) ?? '', '/');

        $parentActive =
          ($link['active'] ?? false)
          || (!empty($link['route']) && request()->routeIs($link['route']))
          || (!empty($parentPath) && (request()->is($parentPath) || request()->is($parentPath . '/*')));

        $childActive = false;

        if ($hasChildren) {
          foreach ($link['children'] as $child) {
            $childUrl  = $child['url'] ?? '';
            $childPath = trim(parse_url($childUrl, PHP_URL_PATH) ?? '', '/');

            if ($childPath === 'events') {
              $isActive = request()->is('events');
            } elseif ($childPath === 'events/past') {
              $isActive = request()->is('events/past') || request()->is('events/past/*');
            } else {
              $isActive =
                ($child['active'] ?? false)
                || (!empty($child['route']) && request()->routeIs($child['route']))
                || (!empty($childPath) && request()->is($childPath));
            }

            if ($isActive) {
              $childActive = true;
              break;
            }
          }
        }

        $groupActive = $parentActive || $childActive;
      @endphp

      <li class="nav-item {{ $hasChildren ? 'nav-item--dropdown' : '' }}">
        @if ($hasChildren)
          <div class="nav-dropdown-wrapper">
            <x-nav.link
              :href="$link['url']"
              :class="$link['class'] ?? ''"
              :active="$groupActive"
            >
              {{ $link['label'] }}
            </x-nav.link>

            <button
              type="button"
              class="nav-dropdown-trigger"
              aria-expanded="{{ $childActive ? 'true' : 'false' }}"
            >
              <span class="nav-caret"></span>
            </button>
          </div>

          <ul class="nav-dropdown {{ $childActive ? 'is-open' : '' }}">
            @foreach ($link['children'] as $child)
              @php
                $childUrl  = $child['url'] ?? '';
                $childPath = trim(parse_url($childUrl, PHP_URL_PATH) ?? '', '/');

                if ($childPath === 'events') {
                  $thisChildActive = request()->is('events');
                } elseif ($childPath === 'events/past') {
                  $thisChildActive = request()->is('events/past') || request()->is('events/past/*');
                } else {
                  $thisChildActive =
                    ($child['active'] ?? false)
                    || (!empty($child['route']) && request()->routeIs($child['route']))
                    || (!empty($childPath) && request()->is($childPath));
                }
              @endphp

              <li class="nav-dropdown-item">
                <x-nav.link
                  :href="$child['url']"
                  :class="$child['class'] ?? ''"
                  :active="$thisChildActive"
                >
                  {{ $child['label'] }}
                </x-nav.link>
              </li>
            @endforeach
          </ul>
        @else
          <x-nav.link
            :href="$link['url']"
            :class="$link['class'] ?? ''"
            :active="$parentActive"
          >
            {{ $link['label'] }}
          </x-nav.link>
        @endif
      </li>
    @endforeach
  </ul>
  {{-- Auth Links --}}
  @if ($showAuthLinks)
    <ul class="nav-list nav-auth">
      @auth
        <li class="nav-item">
          <x-nav.link href="{{ route('admin.index', absolute: false) ?? url('/dashboard') }}" :active="request()->routeIs('admin.*','dashboard.*') || request()->is('dashboard','dashboard/*')">
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
          <x-nav.link href="{{ route('login') }}" :active="request()->routeIs('login')">
            Login
          </x-nav.link>
        </li>
      @endauth
    </ul>
  @endif
</div>
</nav>
