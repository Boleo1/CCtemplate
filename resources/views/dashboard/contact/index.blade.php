<x-dashboard-layout>

  <section class="contactSection">
    <div class="contactContainer">
      <h2>Contact Messages:</h2>
      @foreach($messages as $message)
        <div class="contactMessage">
          <h3>{{ $message->name }} ({{ $message->email }})</h3>
          <p><strong>Subject:</strong> {{ $message->subject }}</p>
          <p><strong>Phone:</strong> {{ $message->phone ?? 'N/A' }}</p>
          <p><strong>Message:</strong></p>
          <p>{{ $message->message }}</p>
          <hr>
          <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" class="deleteForm">
              @csrf
              @method('DELETE')
              <button type="submit" class="deleteBtn">Delete</button>
          </form>

        </div>
      @endforeach
    </div>
  </section>
</x-dashboard-layout>