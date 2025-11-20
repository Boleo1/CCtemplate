<x-guest-layout>
  <div class="auth-container">
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-ui.input-label for="name" :value="__('Name')" />
            <x-ui.text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-ui.input-error :messages="$errors->get('name')"/>
        </div>

        <!-- Email Address -->
        <div>
            <x-ui.input-label for="email" :value="__('Email')" />
            <x-ui.text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-ui.input-error :messages="$errors->get('email')"/>
        </div>

        <!-- Password -->
        <div>
            <x-ui.input-label  for="password" :value="__('Password')" />

            <x-ui.text-input id="password"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-ui.input-error :messages="$errors->get('password')"/>
        </div>

        <!-- Confirm Password -->
        <div>
            <x-ui.input-label  for="password_confirmation" :value="__('Confirm Password')" />

            <x-ui.text-input id="password_confirmation"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-ui.input-error :messages="$errors->get('password_confirmation')"/>
        </div>

        <div>
            <a href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-ui.primary-button >
                {{ __('Register') }}
            </x-ui.primary-button>
        </div>
    </form>
  </div>
</x-guest-layout>
