<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nomor Tagihan</th>
            <th>Nomor Kontrak</th>
            <th>Nama Nasabah</th>
            <th>No HP</th>
            <th>Nama Petugas</th>
            <th>Alamat</th>
            <th>Tanggal Penagihan</th>
            <th>Outstanding Awal</th>
            <th>Outstanding Sisa</th>
            <th>Outstanding Total</th>
            <th>Angsuran</th>
            <th>Bank</th>
            <th>Status Penagihan</th>
            <th>Bukti Penagihan</th>
            <th>Tanggal Janji Bayar</th>
            <th>Jumlah Setoran</th>
            <th>Keterangan</th>
            <th>TTD Petugas</th>
            <th>TTD Nasabah</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item => $value)
            <tr>
                <td>{{ $item + 1 }}</td>
                <td>{{ $value->bill_number }}</td>
                <td>{{ $value->customer->no_contract }}</td>
                <td>{{ $value->customer->name_customer }}</td>
                <td>{{ $value->customer->phone_number }}</td>
                <td>{{ $value->user->name }}</td>
                <td>{{ $value->customer->customerAddress->address }}</td>
                <td>{{ $value->latestBillingFollowups->last()->date_exec }}</td>
                <td>{{ $value->customer->os_start }}</td>
                <td>{{ $value->customer->os_remaining }}</td>
                <td>{{ $value->customer->os_total }}</td>
                <td>{{ $value->customer->monthly_installments }}</td>
                <td>{{ $value->customer->bank->name }}</td>
                <td>{{ $value->latestBillingFollowups->last()->status }}</td>
                <td>{{ $value->latestBillingFollowups->last()->proof }}</td>
                <td>{{ $value->latestBillingFollowups->last()->promise_date }}</td>
                <td>{{ $value->latestBillingFollowups->last()->payment_amount }}</td>
                <td>{{ $value->latestBillingFollowups->last()->description }}</td>
                <td>{{ $value->latestBillingFollowups->last()->signature_officer }}</td>
                <td>{{ $value->latestBillingFollowups->last()->signature_customer }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
