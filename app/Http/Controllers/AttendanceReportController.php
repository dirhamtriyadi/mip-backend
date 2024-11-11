<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;

class AttendanceReportController extends Controller
{
    public function index(Request $request)
    {
        // get request start_date and end_date or set default this month
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-t');

        // get all users with attendances between start_date and end_date with deleted_at and deteled_by is null
        // $attendanceReports = User::with(['attendances' => function ($query) use ($start_date, $end_date) {
        //     $query->whereBetween('date', [$start_date, $end_date])->where('deleted_at', null)->where('deleted_by', null);
        // }])->get();
        $attendanceReports = User::with(['attendances' => function ($query) use ($start_date, $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        }, 'leaves' => function ($query) use ($start_date, $end_date) {
            $query->whereBetween('start_date', [$start_date, $end_date]);
        }])->get();

        return view('attendance-reports.index', [
            'data' => $attendanceReports,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }
}
