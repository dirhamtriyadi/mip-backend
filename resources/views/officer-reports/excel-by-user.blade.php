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
                <td>
                    @if ($value->proof)
                        {{-- {!! '<img src="' . $value->proof . '" alt="Bukti" width="100">' !!} --}}
                        {{-- {{ asset('images/customer-billings/' . $value->proof) }} --}}
                        <a href="{{ asset('images/customer-billings/' . $value->proof) }}" target="_blank">Lihat</a>
                    @else
                        -
                    @endif
                </td>
                <td>{{ $value->description }}</td>
                <td>
                    @if ($value->signature_officer)
                        {{-- {!! '<img src="' . $value->signature_officer . '" alt="TTD Petugas" width="100">' !!} --}}
                        {{-- {{ asset('images/customer-billings/' . $value->signature_officer) }} --}}
                        <a href="{{ asset('images/customer-billings/' . $value->signature_officer) }}" target="_blank">Lihat</a>
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if ($value->signature_customer)
                        {{-- {!! '<img src="' . $value->signature_customer . '" alt="TTD Nasabah" width="100">' !!} --}}
                        {{-- {{ asset('images/customer-billings/' . $value->signature_customer) }} --}}
                        <a href="{{ asset('images/customer-billings/' . $value->signature_customer) }}" target="_blank">Lihat</a>
                    @else
                        -
                    @endif
                </td>
            </tr>
        @empty

        @endforelse
    <tbody>
    </tbody>
</table>
