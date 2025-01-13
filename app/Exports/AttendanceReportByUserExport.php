<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class AttendanceReportByUserExport implements FromView
{
    public function __construct($start_date, $end_date, $user_id)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->user_id = $user_id;
    }

    public function view(): View
    {
        $start_date = $this->start_date;
        $end_date = $this->end_date;
        $user_id = $this->user_id;
        
        $attendanceReportsByUser = User::with(['attendances' => function ($query) use ($start_date, $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        }, 'leaves' => function ($query) use ($start_date, $end_date, $user_id) {
            $query->where('user_id', $user_id);
            $query->whereBetween('start_date', [$start_date, $end_date]);
            $query->where('status', 'approved');
        }])->where('id', $user_id)->first();

        return view('attendance-reports.excel-by-user', [
            'data' => $attendanceReportsByUser
        ]);
    }
}
