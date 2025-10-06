<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div>
        <div >
          <h2 class="eventsHeading">You are logged in!</h2>
            <div>
              <x-event-form />
            </div>
        </div>
    </div>
</x-app-layout>
