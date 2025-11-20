@props(['dashboard' => false])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">


        <title>{{ 'Laravel' }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
      <x-ui.header />
        <div class="pageWrapper">
          @if (session('success') || session('error'))
            <div 
              class="flash {{ session('success') ? 'flash-success' : 'flash-error' }}" 
              id="flash-message"
            >
              {{ session('success') ?? session('error') }}
            </div>
          @endif

            <main class="{{ $dashboard ?? false ? 'app-dashboard' : 'app-public' }}">
              {{-- Page Content Goes Here--}}
                {{ $slot }}
            </main>
        </div>
        <x-ui.footer />
    </body>
</html>
