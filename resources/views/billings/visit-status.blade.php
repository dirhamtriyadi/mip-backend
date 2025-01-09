@php
    $lastStatus = $value->billingStatuses->last();
@endphp

@if ($lastStatus && $lastStatus->status == 'visit')
    <span class="badge badge-info">{{ $lastStatus->status }}</span>
@elseif ($lastStatus && $lastStatus->status == 'promise_to_pay')
    <span class="badge badge-warning">{{ $lastStatus->status }}</span>
@elseif($lastStatus && $lastStatus->status == 'pay')
    <span class="badge badge-success">{{ $lastStatus->status }}</span>
@else
    <span class="badge badge-danger">{{ $lastStatus->status ?? '-' }}</span>
@endif