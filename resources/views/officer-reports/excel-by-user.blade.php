<div style="margin-bottom: 20px;">
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
        <tbody>
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
                            <a href="{{ asset('images/customer-billings/' . $value->proof) }}" target="_blank">Lihat</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $value->description }}</td>
                    <td>
                        @if ($value->signature_officer)
                            <a href="{{ asset('images/customer-billings/' . $value->signature_officer) }}"
                                target="_blank">Lihat</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if ($value->signature_customer)
                            <a href="{{ asset('images/customer-billings/' . $value->signature_customer) }}"
                                target="_blank">Lihat</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 20px;">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Customer</th>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data->prospectiveCustomerSurveys as $item => $value)
                <tr>
                    <td>{{ $item + 1 }}</td>
                    <td>{{ $value->name }}</td>
                    <td>{{ $value->updated_at }}</td>
                    <td>{{ $value->status }}</td>
                </tr>
            @empty
            @endforelse
        </tbody>
    </table>
</div>
