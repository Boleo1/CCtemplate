<nav class="nav-top">
  <div class="nav-actions">
    @auth
      <span class="user-chip">{{ auth()->user()->name }}</span>
      <form method="POST" action="{{ route('logout') }}" class="inline">
        @csrf
        <button class="btn-outline">Logout</button>
      </form>
    @endauth
  </div>
</nav>
