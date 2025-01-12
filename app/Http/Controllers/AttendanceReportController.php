<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\AttendanceReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class AttendanceReportController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            // 'auth',
            // new Middleware('subscribed', except: ['store']),
            new Middleware('permission:laporan-absen.index', only: ['index']),
            new Middleware('permission:laporan-absen.create', only: ['index', 'create', 'store']),
            new Middleware('permission:laporan-absen.edit', only: ['index', 'edit', 'update']),
            new Middleware('permission:laporan-absen.delete', only: ['index', 'destroy']),
        ];
    }

    public function index(Request $request)
    {
        // get request start_date and end_date or set default this month
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-t');

        return view('attendance-reports.index', [
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }

    // Fetch data for DataTable
    public function fetchDataTable(Request $request)
    {
        // get request start_date and end_date or set default this month
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-t');

        // get all users with attendances between start_date and end_date with deleted_at and deteled_by is null
        $attendanceReports = User::with(['attendances' => function ($query) use ($start_date, $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        }, 'leaves' => function ($query) use ($start_date, $end_date) {
            $query->whereBetween('start_date', [$start_date, $end_date]);
        }])->get();

        return DataTables::of($attendanceReports)
            ->addIndexColumn()
            ->addColumn('name', function ($attendanceReport) {
                return $attendanceReport->name;
            })
            ->addColumn('present', function ($attendanceReport) {
                return $attendanceReport->attendances->where('status', 'present')->count();
            })
            ->addColumn('present_late', function ($attendanceReport) {
                return $attendanceReport->attendances->where('type', 'present')->where('late_duration', '<', 0)->count();
            })
            ->addColumn('present_early_leave', function ($attendanceReport) {
                return $attendanceReport->attendances->where('type', 'present')->where('early_leave_duration', '<', 0)->count();
            })
            ->addColumn('sick', function ($attendanceReport) {
                return $attendanceReport->attendances->where('status', 'sick')->count();
            })
            ->addColumn('permit', function ($attendanceReport) {
                return $attendanceReport->attendances->where('status', 'permit')->count();
            })
            ->addColumn('leave', function ($attendanceReport) {
                return view('attendance-reports.leave', [
                    'value' => $attendanceReport,
                ]);
            })
            ->addColumn('action', function ($attendanceReport) {
                return view('attendance-reports.action', [
                    'value' => $attendanceReport,
                ]);
            })
            ->rawColumns(['leave', 'action'])
            ->toJson();
    }

    // public function fetchDataTable(Request $request)
    // {
    //     $columns = [
    //         0 => 'id',
    //         1 => 'name',
    //         2 => 'present',
    //         3 => 'present_late',
    //         4 => 'present_early_leave',
    //         5 => 'sick',
    //         6 => 'permit',
    //         7 => 'leave',
    //         8 => 'action',
    //     ];

    //     $orderBy = $columns[$request->input('order.0.column')];
    //     $orderDirection = $request->input('order.0.dir');
    //     $searchValue = $request->input('search.value');

    //     $query = User::with('attendances')
    //         ->where('name', 'like', '%' . $searchValue . '%')
    //         ->orWhereHas('attendances', function ($query) use ($searchValue) {
    //             $query->where('date', 'like', '%' . $searchValue . '%');
    //         });

    //     if ($request->has('start_date') && $request->has('end_date')) {
    //         $startDate = $request->input('start_date');
    //         $endDate = $request->input('end_date');
    //         $query->whereHas('attendances', function ($query) use ($startDate, $endDate) {
    //             $query->whereBetween('date', [$startDate, $endDate]);
    //         });
    //     }

    //     $totalFiltered = $query->count();

    //     $users = $query->orderBy($orderBy, $orderDirection)
    //         ->offset($request->input('start'))
    //         ->limit($request->input('length'))
    //         ->get();

    //     $data = [];
    //     foreach ($users as $index => $user) {
    //         $data[] = [
    //             'DT_RowIndex' => $index + 1,
    //             'name' => $user->name,
    //             'present' => $user->attendances->where('type', 'present')->count(),
    //             'present_late' => $user->attendances->where('type', 'present')->where('late_duration', '>', 0)->count(),
    //             'present_early_leave' => $user->attendances->where('type', 'present')->where('early_leave_duration', '>', 0)->count(),
    //             'sick' => $user->attendances->where('type', 'sick')->count(),
    //             'permit' => $user->attendances->where('type', 'permit')->count(),
    //             'leave' => $user->attendances->where('type', 'leave')->count(),
    //             'action' => '<a href="' . route('attendance-reports.show', $user->id) . '" class="btn btn-primary btn-sm">Detail</a>',
    //         ];
    //     }

    //     return response()->json([
    //         'draw' => $request->input('draw'),
    //         'recordsTotal' => User::count(),
    //         'recordsFiltered' => $totalFiltered,
    //         'data' => $data,
    //     ]);
    // }

    public function export(Request $request)
    {
        // get request start_date and end_date or set default this month
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-t');

        return Excel::download(new AttendanceReportExport($start_date, $end_date), Carbon::now()->toDateString() . '-billing-reports.xls');
    }
}
