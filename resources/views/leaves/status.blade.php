@if ($value->status == 'pending')
    <span class="badge badge-warning">{{ $value->status }}</span>
@elseif ($value->status == 'approved')
    <span class="badge badge-success">{{ $value->status }}</span>
@elseif($value->status == 'rejected')
    <span class="badge badge-danger">{{ $value->status }}</span>
@else
    <span class="badge badge-danger">{{ $value->status }}</span>
@endif
