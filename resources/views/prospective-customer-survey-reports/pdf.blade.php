@php
    use App\Enums\ProspectiveCustomerSurveyStatusEnum;
@endphp
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
            <h1>Laporan Survei</h1>
            <p>Periode: {{ $start_date }} s/d {{ $end_date }}</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Petugas</th>
                    <th>Total Survei Belum Selesai</th>
                    <th>Total Survei Selesai</th>
                    <th>Total Survei</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item => $value)
                    <tr>
                        <td>{{ $item + 1 }}</td>
                        <td>{{ $value->name }}</td>
                        <td>{{ $value->prospectiveCustomerSurveys->whereIn('status', [ProspectiveCustomerSurveyStatusEnum::Pending, ProspectiveCustomerSurveyStatusEnum::Ongoing])->count() }}
                        </td>
                        <td>{{ $value->prospectiveCustomerSurveys->where('status', ProspectiveCustomerSurveyStatusEnum::Done)->count() }}
                        </td>
                        <td>{{ $value->prospectiveCustomerSurveys->count() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
