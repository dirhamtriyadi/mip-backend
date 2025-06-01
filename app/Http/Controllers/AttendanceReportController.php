<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\AttendanceReportExport;
use App\Exports\AttendanceReportByUserExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

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
            new Middleware('permission:laporan-kehadiran.index', only: ['index']),
            new Middleware('permission:laporan-kehadiran.create', only: ['index', 'create', 'store']),
            new Middleware('permission:laporan-kehadiran.edit', only: ['index', 'edit', 'update']),
            new Middleware('permission:laporan-kehadiran.delete', only: ['index', 'destroy']),
        ];
    }

    public function index(Request $request)
    {
        // get request start_date and end_date or set default this month
        $start_date = $request->start_date ?? date('Y-01-01');
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
        $start_date = $request->start_date ?? date('Y-01-01');
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
                return '<span class="badge badge-success">' . $attendanceReport->attendances->where('type', 'present')->count() . '</span>';
            })
            ->addColumn('absent', function ($attendanceReport) {
                return '<span class="badge badge-danger">' . $attendanceReport->attendances->where('type', 'absent')->count() . '</span>';
            })
            ->addColumn('present_late', function ($attendanceReport) {
                return '<span class="badge badge-warning">' . $attendanceReport->attendances->where('type', 'present')->where('late_duration', '>', 0)->count() . '</span>';
            })
            ->addColumn('present_early_leave', function ($attendanceReport) {
                return '<span class="badge badge-warning">' . $attendanceReport->attendances->where('type', 'present')->where('early_leave_duration', '>', 0)->count() . '</span>';
            })
            ->addColumn('sick', function ($attendanceReport) {
                return '<span class="badge badge-primary">' . $attendanceReport->attendances->where('type', 'sick')->count() . '</span>';
            })
            ->addColumn('permit', function ($attendanceReport) {
                return '<span class="badge badge-secondary">' . $attendanceReport->attendances->where('type', 'permit')->count() . '</span>';
            })
            ->addColumn('leave', function ($attendanceReport) {
                return view('attendance-reports.leave', [
                    'value' => $attendanceReport,
                ]);
            })
            ->addColumn('action', function ($attendanceReport) use ($start_date, $end_date) {
                return view('attendance-reports.action', [
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'value' => $attendanceReport,
                ]);
            })
            ->rawColumns(['present', 'absent', 'present_late', 'present_early_leave', 'sick', 'permit', 'leave', 'action'])
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

    public function show(Request $request, string $id)
    {
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-t');

        $attendanceReport = User::with(['attendances' => function ($query) use ($start_date, $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        }, 'leaves' => function ($query) use ($start_date, $end_date) {
            $query->whereBetween('start_date', [$start_date, $end_date]);
        }])->findOrFail($id);

        return view('attendance-reports.show', [
            'attendanceReport' => $attendanceReport,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }

    public function fetchDataTableByUser(Request $request)
    {
        // Ambil start_date dan end_date dari request atau set default ke bulan ini
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-t');
        $id = $request->id;

        // Query user dengan filtering role dan customerBillingFollowups
        $attendanceReport = User::with(['attendances' => function ($query) use ($start_date, $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        }, 'leaves' => function ($query) use ($start_date, $end_date) {
            $query->whereBetween('start_date', [$start_date, $end_date]);
        }])->findOrFail($id);

        return DataTables::of($attendanceReport->attendances)
            ->addIndexColumn()
            ->editColumn('date', function ($data) {
                return $data->date ? Carbon::parse($data->date)->format('d/m/Y') : '-';
            })
            ->rawColumns(['status'])
            ->toJson();
    }

    public function export(Request $request)
    {
        // get request start_date and end_date or set default this month
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-t');

        return Excel::download(new AttendanceReportExport($start_date, $end_date), Carbon::now()->toDateString() . '-attendance-reports.xls');
    }

    public function exportByUser(Request $request)
    {
        // get request start_date and end_date or set default this month
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-t');
        $user_id = $request->user_id;

        if(!$user_id){
            return redirect()->back()->with('error', 'User id is required');
        }

        $user = User::find($user_id);

        if(!$user){
            return redirect()->back()->with('error', 'User not found');
        }

        return Excel::download(new AttendanceReportByUserExport($start_date, $end_date, $request->user_id), Carbon::now()->toDateString() . '-attendance-reports-' . $user->name . '.xls');
    }

    public function exportPdf(Request $request)
    {
        // get request start_date and end_date or set default this month
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-t');

        $attendanceReports = User::with(['customerBillingFollowups' => function ($query) use ($start_date, $end_date) {
            $query->whereBetween('created_at', [$start_date, $end_date]);
        }, 'roles'])->whereHas('roles', function($query) {
            $query->where('name', 'Surveyor')->orWhere('name', 'Penagih');
        })->get();

        $pdf = Pdf::loadView('attendance-reports.pdf', [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'data' => $attendanceReports,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('pdf');
    }
}
