@props(['href' => '#'])

@php
  $isActive = request()->is(trim($href, '/')) || request()->is(trim($href, '/').'/*');
  $classes = 'nav-link'.($isActive ? ' is-active' : '');
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
  {{ $slot }}
</a>

