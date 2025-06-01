@php
    use App\Enums\ProspectiveCustomerSurveyStatusEnum;
@endphp
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
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
