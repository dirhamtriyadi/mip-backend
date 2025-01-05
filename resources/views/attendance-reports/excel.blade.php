<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Hadir</th>
            <th>Masuk Terlambat</th>
            <th>Pulang Lebih Awal</th>
            <th>Sakit</th>
            <th>Izin</th>
            <th>Cuti</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item => $value)
            <tr>
                <td>{{ $item + 1 }}</td>
                <td>{{ $value->name }}</td>
                <td>{{ $value->attendances->where('status', 'present')->count() }}</td>
                <td>{{ $value->attendances->where('type', 'present')->where('late_duration', '<', 0)->count() }}</td>
                <td>{{ $value->attendances->where('type', 'present')->where('early_leave_duration', '<', 0)->count() }}</td>
                <td>{{ $value->attendances->where('status', 'sick')->count() }}</td>
                <td>{{ $value->attendances->where('status', 'permit')->count() }}</td>
                <td>
                    <b>Total cuti: </b>{{ $value->leaves->where('status', 'approved')->count() }}<br>
                    @forelse ($value->leaves->where('status', 'approved') as $item)
                        {{-- if latest data not add comma --}}
                        {{ Carbon\Carbon::parse($item->start_date)->format('d-m-Y') }} s/d
                        {{ Carbon\Carbon::parse($item->start_date)->format('d-m-Y') }}{{ $loop->last ? '' : ', ' }}
                    @empty
                        0
                    @endforelse
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
