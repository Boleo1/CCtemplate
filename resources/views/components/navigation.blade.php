@props(['links' => [], 'showAuthLinks' => true])

<nav {{ $attributes->class(['nav-bar']) }}>
  <ul>
      @foreach($links as $link)
      <li>
        <a href="{{ $link['url'] }}" class="{{ $link['class'] ?? '' }}" id="{{ $link['id'] ?? '' }}">{{ $link['label'] }}</a>
      </li>
      @endforeach
 
      @if(Auth::check() && $showAuthLinks)
      <li>
        <a href="/dashboard" class="navDashboard">Dashboard</a>
      </li>
      <li>
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <x-button class="btn-secondary">
            Logout
          </x-button>
        </form>
        </li>
        @endif
  </ul>