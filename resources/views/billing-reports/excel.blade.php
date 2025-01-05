<table>
    <thead>
        <tr>
            <th>No</th>
            <th>No Kontrak</th>
            <th>Nama</th>
            <th>No HP</th>
            <th>Alamat</th>
            <th>Tanggal</th>
            <th>Total Tagihan</th>
            <th>Angsuran</th>
            <th>Bank</th>
            <th>Tujuan Penagihan</th>
            <th>Bukti Kunjungan</th>
            <th>Keterangan (Kunjungan)</th>
            <th>Tanggal Janji Bayar</th>
            <th>Bukti Janji Bayar</th>
            <th>Keterangan (Janji Bayar)</th>
            <th>Jumlah Setoran</th>
            <th>Bukti Setoran</th>
            <th>Keterangan (Setoran)</th>
            <th>TTD Petugas</th>
            <th>TTD Nasabah</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item => $value)
            <tr>
                <td>{{ $item + 1 }}</td>
                <td>{{ $value->customer->no }}</td>
                <td>{{ $value->customer->name_customer }}</td>
                <td>{{ $value->customer->phone_number }}</td>
                <td>{{ $value->customer->address }}</td>
                <td>{{ $value->customer->total_bill }}</td>
                <td>{{ $value->customer->installment }}</td>
                <td>{{ $value->customer->name_bank }}</td>
                <td>{{ $value->destination }}</td>
                <td>{{ $value->image_visit }}</td>
                <td>{{ $value->description_visit }}</td>
                <td>{{ $value->promise_date }}</td>
                <td>{{ $value->image_promise }}</td>
                <td>{{ $value->description_promise }}</td>
                <td>{{ $value->amount }}</td>
                <td>{{ $value->image_amount }}</td>
                <td>{{ $value->description_amount }}</td>
                <td>{{ $value->signature_officer }}</td>
                <td>{{ $value->signature_customer }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
