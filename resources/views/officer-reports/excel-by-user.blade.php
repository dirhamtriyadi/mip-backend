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
            <th>Nomor Tagihan</th>
            <th>Nama Nasabah</th>
            <th>Alamat</th>
            <th>Desa</th>
            <th>Kecamatan</th>
            <th>Bank</th>
            <th>Status Penagihan</th>
            <th>Tanggal Penagihan</th>
            <th>Tanggal Janji Bayar</th>
            <th>Jumlah Bayar</th>
            <th>Bukti (Kunjungan/Janji Bayar/Bayar)</th>
            <th>Keterangan Kunjungan</th>
            <th>TTD Petugas</th>
            <th>TTD Nasabah</th>
        </tr>
    </thead>
        @forelse ($data->customerBillingFollowups as $item => $value)
            <tr>
                <td>{{ $item + 1 }}</td>
                <td>{{ $value->customerBilling->bill_number }}</td>
                <td>{{ $value->customerBilling->customer->name_customer }}</td>
                <td>{{ $value->customerBilling->customer->customerAddress->address }}</td>
                <td>{{ $value->customerBilling->customer->customerAddress->subdistrict }}</td>
                <td>{{ $value->customerBilling->customer->customerAddress->village }}</td>
                <td>{{ $value->customerBilling->customer->bank->name }}</td>
                <td>{{ $value->status }}</td>
                <td>{{ $value->date_exec }}</td>
                <td>{{ $value->promise_date }}</td>
                <td>{{ $value->payment_amount }}</td>
                <td>{{ $value->proof }}</td>
                <td>{{ $value->description }}</td>
                <td>
                    @if ($value->signature_officer)
                        {!! '<img src="' . $value->signature_officer . '" alt="TTD Petugas" width="100">' !!}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if ($value->signature_customer)
                        {!! '<img src="' . $value->signature_customer . '" alt="TTD Nasabah" width="100">' !!}
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>Total</td>
                <td></td>
                <td>{{ $value->total_amount }}</td>
                <td></td>
                <td><a href="{{ asset('images/customer-billings/' . $value->proof) }}" target="_blank">Lihat</a></td>
                <td></td>
                <td colspan="2"></td>
            </tr>
        @empty

        @endforelse
    <tbody>
    </tbody>
</table>
