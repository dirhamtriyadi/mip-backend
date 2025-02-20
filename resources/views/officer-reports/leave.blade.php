<b>Total cuti: </b>{{ $value->leaves->where('status', 'approved')->count() }}<br>
@forelse ($value->leaves->where('status', 'approved') as $item)
    {{-- if latest data not add comma --}}
    {{ Carbon\Carbon::parse($item->start_date)->format('d-m-Y') }} s/d
    {{ Carbon\Carbon::parse($item->start_date)->format('d-m-Y') }}{{ $loop->last ? '' : ', ' }}
@empty
    0
@endforelse
