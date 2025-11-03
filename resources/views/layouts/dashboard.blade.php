<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>@yield('title', 'Dashboard')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  {{-- Your dashboard CSS --}}
  <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
  @stack('head')
</head>
<body class="dash-body">
  <header class="dash-topbar">
    <x-dashboard.nav />
  </header>

  <div class="dash-shell">
    <aside class="dash-sidebar" id="dashSidebar">
      <x-dashboard.sidebar />
    </aside>

    <main class="dash-main">
      {{ $slot }}
    </main>
  </div>
</body>
</html>
