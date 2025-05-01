<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Visit</th>
            <th>Janji Bayar</th>
            <th>Bayar</th>
            <th>Total Bayar</th>
            <th>Survey</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item => $value)
            @php
                $doneCount = $value->prospectiveCustomerSurveys()->where('status', 'done')->count();

                $pendingOrOngoingCount = $value
                    ->prospectiveCustomerSurveys()
                    ->where(function ($query) {
                        $query->where('status', 'pending')->orWhere('status', 'ongoing');
                    })
                    ->count();
            @endphp
            <tr>
                <td>{{ $item + 1 }}</td>
                <td>{{ $value->name }}</td>
                <td>{{ $value->customerBillingFollowups->where('status', 'visit')->count() ?? 0 }}</td>
                <td>{{ $value->customerBillingFollowups->where('status', 'promise_to_pay')->count() ?? 0 }}</td>
                <td>{{ $value->customerBillingFollowups->where('status', 'pay')->count() ?? 0 }}</td>
                <td>{{ $value->customerBillingFollowups->where('status', 'pay')->sum('payment_amount') ?? 0 }}</td>
                <td>Selesai: {{ $doneCount }} <br /> Belum Selesai: {{ $pendingOrOngoingCount }} <br /> Total:
                    {{ $doneCount + $pendingOrOngoingCount }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
