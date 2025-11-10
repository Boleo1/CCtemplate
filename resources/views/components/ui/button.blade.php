<button {{ $attributes->merge(['class' => '' ?? 'btn-primary', 'type' => '' ?? 'submit']) }}>
    {{ $slot }}
  </button>