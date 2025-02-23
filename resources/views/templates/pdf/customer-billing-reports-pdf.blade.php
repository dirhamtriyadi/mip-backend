<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .table {
            border-collapse: collapse;
            width: 100%;
        }

        .table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table .thead {
            background-color: darkgrey;
            color: black;
            font-size: 0.5em;
        }

        .table .tbody {
            font-size: 0.5em;
        }
    </style>
</head>

<body>
    <div>
        <h3>Laporan Penagihan - {{ \Carbon\Carbon::parse($start_date)->format('d-m-Y') }} s/d
            {{ \Carbon\Carbon::parse($end_date)->format('d-m-Y') }}</h3>
        <hr>
        <h4>Petugas</h4>
        <table>
            <tr class="text-center">
                <td>Nama</td>
                <td>:</td>
                <td>{{ $user->name ?? '-' }}</td>
            </tr>
            <tr>
                <td>NIP</td>
                <td>:</td>
                <td>{{ $user->nip ?? '-' }}</td>
            </tr>
        </table>
        <hr>
        <h4>Data Laporan</h4>
        <table class="table">
            <thead class="thead">
                <tr>
                    <th>No</th>
                    <th>Nomor Tagihan</th>
                    <th>Nama Nasabah</th>
                    <th>Tanggal</th>
                    <th>Status Penagihan</th>
                    <th>Status Kunjungan</th>
                    <th>Tanggal Janji Bayar</th>
                    <th>Jumlah Bayar</th>
                    <th>Bukti (Kunjungan/Janji Bayar/Bayar)</th>
                    <th>Keterangan</th>
                    <th>TTD Petugas</th>
                    <th>TTD Nasabah</th>
                </tr>
            </thead>
            <tbody class="tbody">
                @forelse ($data as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->no_billing ?? '-' }}</td>
                        <td>{{ $item->customer->name_customer }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->date)->format('d-m-Y') }}</td>
                        <td><span class="badge badge-{{ $item->status->color() }}">{{ $item->status->label() }}</span>
                        </td>
                        <td><span
                                class="badge badge-{{ $item->latestBillingStatus ? $item->latestBillingStatus->status->color() : '-' }}">{{ $item->latestBillingStatus ? $item->latestBillingStatus->status->label() : '-' }}</span>
                        </td>
                        <td>{{ $item->latestBillingStatus ? ($item->latestBillingStatus->promise_date ? \Carbon\Carbon::parse($item->latestBillingStatus->promise_date)->format('d-m-Y') : '-') : '-' }}
                        </td>
                        <td>{{ $item->latestBillingStatus ? ($item->latestBillingStatus->payment_amount ? number_format($item->latestBillingStatus->payment_amount, 0, ',', '.') : '-') : '-' }}
                        </td>
                        <td>{!! $item->latestBillingStatus
                            ? ($item->latestBillingStatus->evidence
                                ? '<img src="' .
                                    public_path('images/customer-billings/' . $item->latestBillingStatus->evidence) .
                                    '" alt="evidence" style="width: 100px; height: 100px;">'
                                : '-')
                            : '-' !!}</td>
                        <td>{{ $item->latestBillingStatus->description ?? '-' }}</td>
                        <td>{!! $item->latestBillingStatus
                            ? ($item->latestBillingStatus->signature_officer
                                ? '<img src="' .
                                    public_path('images/customer-billings/' . $item->latestBillingStatus->signature_officer) .
                                    '" alt="signature_officer" style="width: 100px; height: 100px;">'
                                : '-')
                            : '-' !!}</td>
                        <td>{!! $item->latestBillingStatus
                            ? ($item->latestBillingStatus->signature_customer
                                ? '<img src="' .
                                    public_path('images/customer-billings/' . $item->latestBillingStatus->signature_customer) .
                                    '" alt="signature_customer" style="width: 100px; height: 100px;">'
                                : '-')
                            : '-' !!}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>

</html>
