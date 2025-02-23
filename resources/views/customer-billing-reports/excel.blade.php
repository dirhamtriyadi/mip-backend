<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nomor Tagihan</th>
            <th>Nomor Kontrak</th>
            <th>Nama Nasabah</th>
            <th>Nama Ibu</th>
            <th>No HP</th>
            <th>Nama Petugas</th>
            <th>Alamat</th>
            <th>Tanggal Jatuh Tempo</th>
            <th>Margin Awal</th>
            <th>Outstanding Awal</th>
            <th>Margin Sisa</th>
            <th>Angsuran</th>
            <th>Tunggak Bulan</th>
            <th>Tunggakakan</th>
            <th>Tanggal Penagihan</th>
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
            @php
                $latestFollowup = $value->latestBillingFollowups->last() ?? null;
            @endphp
            <tr>
                <td>{{ $item + 1 }}</td>
                <td>{{ $value->bill_number }}</td>
                <td>{{ $value->customer->no_contract }}</td>
                <td>{{ $value->customer->name_customer }}</td>
                <td>{{ $value->customer->name_mother ?? '-' }}</td>
                <td>{{ $value->customer->phone_number ?? '-' }}</td>
                <td>{{ $value->user->name ?? '-' }}</td>
                <td>{{ $value->customer->customerAddress->address ?? '-' }}</td>
                <td>{{ $value->customer->due_date ?? '-' }}</td>
                <td>{{ $value->customer->margin_start ?? '-' }}</td>
                <td>{{ $value->customer->os_start ?? '-' }}</td>
                <td>{{ $value->customer->margin_remaining ?? '-' }}</td>
                <td>{{ $value->customer->installments ?? '-' }}</td>
                <td>{{ $value->customer->month_arrears ?? '-' }}</td>
                <td>{{ $value->customer->arrears ?? '-' }}</td>
                <td>{{ $value->latestBillingFollowups->last()->date_exec ?? '-' }}</td>
                <td>{{ $value->customer->bank->name ?? '-' }}</td>
                <td>{{ $value->latestBillingFollowups->last()->status ?? '-' }}</td>
                <td>
                    @if ($latestFollowup && $latestFollowup->proof)
                        {{ asset('images/customer-billings/' . $latestFollowup->proof) }}
                    @else
                        -
                    @endif
                </td>
                <td>{{ $value->latestBillingFollowups->last()->promise_date ?? '-' }}</td>
                <td>{{ $value->latestBillingFollowups->last()->payment_amount ?? '-' }}</td>
                <td>{{ $value->latestBillingFollowups->last()->description ?? '-' }}</td>
                <td>
                    @if ($latestFollowup && $latestFollowup->signature_officer)
                        {{ asset('images/customer-billings/' . $latestFollowup->signature_officer) }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if ($latestFollowup && $latestFollowup->signature_customer)
                        {{ asset('images/customer-billings/' . $latestFollowup->signature_customer) }}
                    @else
                        -
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
