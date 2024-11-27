@if ($value->type == 'present')
    <span class="badge badge-success">{{ $value->type }}</span>
@elseif ($value->type == 'sick')
    <span class="badge badge-info">{{ $value->type }}</span>
@elseif($value->type == 'permit')
    <span class="badge badge-danger">{{ $value->type }}</span>
@else
    <span class="badge badge-warning">{{ $value->type }}</span>
@endif
