<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class OfficerReportController extends Controller
{
    public function index(Request $request)
    {
        // get request start_date and end_date or set default this month
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-t');

        return view('officer-reports.index', [
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
        $users = User::with(['attendances' => function ($query) use ($start_date, $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        }, 'leaves' => function ($query) use ($start_date, $end_date) {
            $query->whereBetween('start_date', [$start_date, $end_date]);
        }])->get();

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('name', function ($user) {
                return $user->name;
            })
            ->addColumn('visit', function ($user) {
                return optional($user->customerBillingFollowups)->where('status', 'visit')->count() ?? 0;
            })
            ->addColumn('promise_to_pay', function ($user) {
                return optional($user->customerBillingFollowups)->where('status', 'promise_to_pay')->count() ?? 0;
            })
            ->addColumn('pay', function ($user) {
                return optional($user->customerBillingFollowups)->where('status', 'pay')->count() ?? 0;
            })
            ->addColumn('total_pay', function ($user) {
                return optional($user->customerBillingFollowups)->where('status', 'pay')->sum('payment_amount') ?? 0;
            })
            ->addColumn('leave', function ($user) {
                return view('attendance-reports.leave', [
                    'value' => $user,
                ]);
            })
            ->addColumn('action', function ($user) use ($start_date, $end_date) {
                return view('attendance-reports.action', [
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'value' => $user,
                ]);
            })
            ->rawColumns(['leave', 'action'])
            ->toJson();
    }

    // public function export(Request $request)
    // {
    //     // get request start_date and end_date or set default this month
    //     $start_date = $request->start_date ?? date('Y-m-01');
    //     $end_date = $request->end_date ?? date('Y-m-t');

    //     return Excel::download(new AttendanceReportExport($start_date, $end_date), Carbon::now()->toDateString() . '-attendance-reports.xls');
    // }

    // public function exportByUser(Request $request)
    // {
    //     // get request start_date and end_date or set default this month
    //     $start_date = $request->start_date ?? date('Y-m-01');
    //     $end_date = $request->end_date ?? date('Y-m-t');
    //     $user_id = $request->user_id;

    //     if(!$user_id){
    //         return redirect()->back()->with('error', 'User id is required');
    //     }

    //     $user = User::find($user_id);

    //     if(!$user){
    //         return redirect()->back()->with('error', 'User not found');
    //     }

    //     return Excel::download(new AttendanceReportByUserExport($start_date, $end_date, $request->user_id), Carbon::now()->toDateString() . '-attendance-reports-' . $user->name . '.xls');
    // }
}
