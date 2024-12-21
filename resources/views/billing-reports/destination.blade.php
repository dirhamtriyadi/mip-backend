@if ($value->destination == 'visit')
    <span class="badge badge-info">{{ $value->destination }}</span>
@elseif ($value->destination == 'promise_date')
    <span class="badge badge-primary">{{ $value->destination }}</span>
@elseif($value->destination == 'pay')
    <span class="badge badge-success">{{ $value->destination }}</span>
@else
    <span class="badge badge-warning">{{ $value->destination }}</span>
@endif
