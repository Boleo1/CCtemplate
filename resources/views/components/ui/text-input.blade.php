@props(['disabled' => false])

<input @disabled($disabled){{ $attributes->merge(['class' => 'authInputs', 'type' => '' ?? 'textarea' , 'required' => false,]) }}>
