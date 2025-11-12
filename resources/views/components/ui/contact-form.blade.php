@if (session('status'))
  <div class="alert success">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('contact.submit') }}" class="contact-form" novalidate>
  @csrf

  {{-- Honeypot for bots --}}
  <input type="text" name="website" tabindex="-1" autocomplete="off" style="display:none">

  <div class="row">
    <x-ui.input-label for="name" :value="'Name'" />
    <input id="name" name="name" type="text" value="{{ old('name') }}" required>
    <x-ui.input-error :messages="$errors->get('name')" />
  </div>

  <div class="row">
    <x-ui.input-label for="email" :value="'Email'" />
    <input id="email" name="email" type="email" value="{{ old('email') }}" required>
    <x-ui.input-error :messages="$errors->get('email')" />
  </div>

  <div class="row">
    <x-ui.input-label for="phone" :value="'Phone (optional)'" />
    <input id="phone" name="phone" type="text" value="{{ old('phone') }}">
    <x-ui.input-error :messages="$errors->get('phone')" />
  </div>

  <div class="row">
    <x-ui.input-label for="subject" :value="'Subject (optional)'" />
    <input id="subject" name="subject" type="text" value="{{ old('subject') }}">
    <x-ui.input-error :messages="$errors->get('subject')" />
  </div>

  <div class="row">
    <x-ui.input-label for="message" :value="'Message'" />
    <textarea id="message" name="message" rows="6" required>{{ old('message') }}</textarea>
    <x-ui.input-error :messages="$errors->get('message')" />
  </div>

  <button type="submit" class="btn btn-primary">Send Message</button>
</form>
