<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class AttendanceReportExport implements FromView
{
    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function view(): View
    {
        $start_date = $this->start_date;
        $end_date = $this->end_date;

        $attendanceReports = User::with(['attendances' => function ($query) use ($start_date, $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        }, 'leaves' => function ($query) use ($start_date, $end_date) {
            $query->whereBetween('start_date', [$start_date, $end_date]);
        }])->get();

        return view('attendance-reports.excel', [
            'data' => $attendanceReports
        ]);
    }
}
