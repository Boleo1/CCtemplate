@props(['value'])
  <label {{ $attributes->merge(['class' => 'inputLabel']) }}>
    {{ $value ?? $slot }}
</label>