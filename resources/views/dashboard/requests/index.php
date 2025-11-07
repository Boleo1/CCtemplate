<x-app-layout>

<h2> Dashboard Requests Page</h2>

  <div class="requests-container">
    <!-- Content for requests can be added here -->
     @foreach($requests as $request)
     
       <div class="request-item">
         <h3>{{ $request->event_type }} - {{ $request->event_date->format('M d, Y') }}</h3>
         <p>Requested by: {{ $request->requested_by }}</p>
         <p>Status: {{ ucfirst($request->status) }}</p>
         <p>Description: {{ $request->event_description }}</p>
         @if($request->status !== 'pending')
           <p>Reviewed by: {{ $request->reviewed_by }}</p>
           <p>Reviewed at: {{ $request->reviewed_at ? $request->reviewed_at->format('M d, Y H:i') : 'N/A' }}</p>
           <p>Review Notes: {{ $request->review_notes ?? 'N/A' }}</p>
         @endif
       </div>
     @endforeach
  </div>
</x-app-layout>