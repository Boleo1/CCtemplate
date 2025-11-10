@props(['links' => [], 'showAuthLinks' => true])

<nav {{ $attributes->class(['nav-bar']) }}>
  <ul>
      @foreach($links as $link)
      <li>
        <a href="{{ $link['url'] }}" class="{{ $link['class'] ?? '' }}" id="{{ $link['id'] ?? '' }}">{{ $link['label'] }}</a>
        @endforeach
      </li>
      <li>
        @if(Auth::check() && $showAuthLinks)
        <a href="/dashboard" class="navDashboard">Dashboard</a>
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <x-ui.button class="btn-secondary">
              Logout
            </x-ui.button>
          </form>
      </li>
        @endif
  
  </ul>
</nav>