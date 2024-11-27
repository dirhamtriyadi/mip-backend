@php
    if (isset($value->location_check_in)) {
        $location_check_in = explode(',', $value->location_check_in);
    }
    if (isset($value->location_check_out)) {
        $location_check_out = explode(',', $value->location_check_out);
    }

    if (isset($value->location_check_in)) {
        $latitude_check_in = $location_check_in[0];
        $longitude_check_in = $location_check_in[1];
    }
    if (isset($value->location_check_out)) {
        $latitude_check_out = $location_check_out[0];
        $longitude_check_out = $location_check_out[1];
    }
@endphp
{!! $value->location_check_in ? '<a href="https://www.google.com/maps/search/?api=1&query=' . $latitude_check_in . ',' . $longitude_check_in .'" target="_blank">Masuk</a>' : '-' !!} {!! $value->location_check_out ? '| <a href="https://www.google.com/maps/search/?api=1&query=' . $latitude_check_out . ',' . $longitude_check_out .'" target="_blank">Pulang</a>' : '| -' !!}
