<x-guest-layout>
  <div class="auth-container">
    <!-- Session Status -->
    <x-ui.auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-ui.input-label for="email" :value="__('Email')" />
            <x-ui.text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-ui.input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div>
            <x-ui.input-label for="password" :value="__('Password')" />

            <x-ui.text-input id="password"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-ui.input-error :messages="$errors->get('password')"/>
        </div>

        <!-- Remember Me -->
        <div>
            <label for="remember_me">
                <input id="remember_me" type="checkbox" name="remember">
                <span>{{ __('Remember me') }}</span>
            </label>
        </div>

        <div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-ui.primary-button>
                {{ __('Log in') }}
            </x-ui.primary-button>
        </div>
    </form>
    </div>
</x-guest-layout>
