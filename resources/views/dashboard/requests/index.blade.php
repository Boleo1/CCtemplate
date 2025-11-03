<x-dashboard-layout>
<div class="requestsHeader">
  <h2>Event Requests</h2>
</div>

<table class="table">
  <thead>
    <tr>
      <th>Event</th>
      <th>Requested By</th>
      <th>Date</th>
      <th>Description</th>
      <th>Status</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    @foreach($requests as $r)
    <tr>
      <td>{{ $r->event_type }}</td>
      <td>{{ $r->requested_by }}</td>
      <td>{{ $r->event_date->format('M d, Y') }} @ {{ $r->event_time }}</td>
      <td>{{ Str::limit($r->event_description, 60) }}</td>
      <td>
        <span class="badge {{ $r->status }}">
          {{ ucfirst($r->status) }}
        </span>
      </td>
      <td>
        @if($r->status === 'pending')
          <form method="POST" action="{{ route('admin.requests.moderate', $r) }}" style="display:inline">
            @csrf @method('PATCH')
            <input type="hidden" name="decision" value="approved">
            <button type="submit" class="btn btn-success btn-sm">Approve</button>
          </form>

          <form method="POST" action="{{ route('admin.requests.moderate', $r) }}" style="display:inline">
            @csrf @method('PATCH')
            <input type="hidden" name="decision" value="rejected">
            <button type="submit" class="btn btn-danger btn-sm">Deny</button>
          </form>
        @else
          <small>{{ ucfirst($r->status) }} by {{ optional($r->reviewer)->name ?? 'Admin' }}</small>
        @endif
      </td>
    </tr>
    @endforeach
  </tbody>
</table>


</x-dashboard-layout>