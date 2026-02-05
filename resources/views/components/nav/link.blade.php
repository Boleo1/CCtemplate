@props([
  'href' => '#',
  'active' => null, // null = auto, true/false = forced
])

@php
  // Normalize href into a path we can match against request()->is()
  $path = trim(parse_url($href, PHP_URL_PATH) ?? '', '/');

  // Auto-detect active ONLY when 'active' isn't explicitly provided
  $autoActive = false;
  if ($path !== '') {
    // Exact match OR subpath match
    $autoActive = request()->is($path) || request()->is($path.'/*');
  }

  // If caller passed :active, respect it. Otherwise use autoActive.
  $isActive = is_null($active)
    ? $autoActive
    : filter_var($active, FILTER_VALIDATE_BOOLEAN);

  $classes = 'nav-link' . ($isActive ? ' is-active' : '');
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
  {{ $slot }}
</a>
