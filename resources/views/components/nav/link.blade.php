@props(['href' => '#', 'active' => false])

@php
  $classes = 'side-link'.($active ? ' is-active' : '');
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
  {{ $slot }}
</a>
