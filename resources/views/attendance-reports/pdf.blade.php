<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        div {
            width: 100%;
            text-align: center;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        p {
            font-size: 14px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        thead {
            background: #f4f4f4;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #ddd;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
    </style>
</head>

<body>
    <div>
        <div>
            <h1>Laporan Kehadiran Karyawan</h1>
            <p>Periode: {{ $start_date }} s/d {{ $end_date }}</p>
        </div>
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
                        <td>{{ $value->attendances->where('type', 'present')->where('late_duration', '<', 0)->count() }}
                        </td>
                        <td>{{ $value->attendances->where('type', 'present')->where('early_leave_duration', '<', 0)->count() }}
                        </td>
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
    </div>
</body>

</html>
