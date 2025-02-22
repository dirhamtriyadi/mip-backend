<table>
    <tr>
        <td>Nama</td>
        <td>:</td>
        <td>{{ $data->name }}</td>
    </tr>
    <tr>
        <td>NIK</td>
        <td>:</td>
        <td>{{ $data->detail_users->nik ?? '-' }}</td>
    </tr>
    <tr></tr>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Absen</th>
            <th>Tanggal</th>
            <th>Jam Masuk</th>
            <th>Jam Pulang</th>
            <th>Masuk Terlambat</th>
            <th>Pulang Lebih Awal</th>
            <th>Tipe Absen</th>
            <th>Keterangan Masuk</th>
            <th>Keterangan Pulang</th>
            <th>Gambar (Masuk dan Pulang)</th>
            <th>Lokasi (Masuk dan Pulang)</th>
        </tr>
    </thead>
        @forelse ($data->attendances as $item => $value)
            <tr>
                <td>{{ $item + 1 }}</td>
                <td>{{ $value->code }}</td>
                <td>{{ $value->date }}</td>
                <td>{{ $value->time_check_in }}</td>
                <td>{{ $value->time_check_out }}</td>
                <td>{{ $value->late_duration }}</td>
                <td>{{ $value->early_leave_duration }}</td>
                <td>{{ $value->type }}</td>
                <td>{{ $value->reason_late }}</td>
                <td>{{ $value->reason_early_out }}</td>
                <td>{{ $value->image_check_in ? asset('images/attendances/' . $value->image_check_in) : '-' }}, {{ $value->image_check_out ? asset('images/attendances/' . $value->image_check_out) : '-' }}</td>
                <td>{{ $value->location_check_in }}, {{ $value->location_check_out }}</td>
            </tr>
        @empty

        @endforelse
    <tbody>
    </tbody>
</table>
