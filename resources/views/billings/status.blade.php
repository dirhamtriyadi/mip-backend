@if ($value->status == 'pending')
    <span class="badge badge-warning">{{ $value->status }}</span>
@elseif ($value->status == 'process')
    <span class="badge badge-info">{{ $value->status }}</span>
@elseif($value->status == 'success')
    <span class="badge badge-success">{{ $value->status }}</span>
@elseif($value->status == 'cancel')
    <span class="badge badge-danger">{{ $value->status }}</span>
@else
    <span class="badge badge-danger">{{ $value->status }}</span>
@endif
