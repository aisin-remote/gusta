<table border="1px">
  <thead>
    <tr>
      <th class="text-center">No</th>
      <th class="text-center">Guest Name</th>
      <th class="text-center">Visit Purpose</th>
      <th class="text-center">Plan Visit</th>
      <th class="text-center">Visit Date</th>
      <th class="text-center">Status</th>
    </tr>
  </thead>
  <tbody class="text-center">
    @foreach ($appointments as $appointment)
    
    <tr>
      <td class="display-4">{{ $loop->iteration }}</td>
      <td class="display-4">{{ $appointment->name }}</td>
      <td class="display-4">{{ $appointment->purpose }}</td>
      <td class="display-4">{{ $appointment->frequency }}</td>
      <td class="display-4">{{ Carbon\Carbon::parse($appointment->start_date)->toFormattedDateString() }} - {{ Carbon\Carbon::parse($appointment->end_date)->toFormattedDateString() }}</td>
      
      @if($appointment->status === 'pending')
      <td>
        <span class="badge badge-pill badge-warning p-2 text-light">{{ $appointment->status }}</span>
      </td>
      @elseif($appointment->status === 'approved')
      <td>
        <span class="badge badge-pill badge-success p-2 text-light">{{ $appointment->status }}</span>
      </td>
      @else
      <td>
        <span class="badge badge-pill badge-danger p-2 text-light">{{ $appointment->status }}</span>
      </td>
      @endif
      
    </tr>
    
    @endforeach
  </tbody>
</table>