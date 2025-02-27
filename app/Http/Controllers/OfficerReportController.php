<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\OfficerReportExport;
use App\Exports\OfficerReportByUserExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $users = User::with(['customerBillingFollowups' => function ($query) use ($start_date, $end_date) {
            $query->whereBetween('created_at', [$start_date, $end_date]);
        }, 'roles'])->whereHas('roles', function($query) {
            $query->where('name', 'Surveyor')->orWhere('name', 'Penagih');
        })->get();

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
            ->addColumn('action', function ($user) use ($start_date, $end_date) {
                return view('officer-reports.action', [
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'value' => $user,
                ]);
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function show(Request $request, string $id)
    {
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-t');

        $officerReport = User::with(['customerBillingFollowups' => function ($query) use ($start_date, $end_date) {
            $query->whereBetween('date_exec', [$start_date, $end_date]);
        }, 'roles'])->whereHas('roles', function($query) {
            $query->where('name', 'Surveyor')->orWhere('name', 'Penagih');
        })->find($id);

        return view('officer-reports.show', [
            'officerReport' => $officerReport,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }

    public function fetchDataTableByOfficer(Request $request)
    {
        // get request start_date and end_date or set default this month
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-t');
        $id = $request->id;

        // get all users with attendances between start_date and end_date with deleted_at and deteled_by is null
        $officerReport = User::with(['customerBillingFollowups' => function ($query) use ($start_date, $end_date) {
            $query->whereBetween('date_exec', [$start_date, $end_date]);
        }, 'roles'])->whereHas('roles', function($query) {
            $query->where('name', 'Surveyor')->orWhere('name', 'Penagih');
        })->find($id);

        return DataTables::of($officerReport->customerBillingFollowups)
            ->addIndexColumn()
            ->editColumn('date_exec', function ($data) {
                return $data->date_exec ? Carbon::parse($data->date_exec)->format('d/m/Y') : '-';
            })
            ->addColumn('name_customer', function ($data) {
                return $data->customerBilling->customer->name_customer ?? '-';
            })
            ->editColumn('status', function ($data) {
                return $data->status ? '<span class="badge badge-{{ $value->status->color() }}">{{ $value->status->label() }}</span>' : '-' ;
            })
            ->rawColumns(['status'])
            ->toJson();
    }

    public function export(Request $request)
    {
        // get request start_date and end_date or set default this month
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-t');

        return Excel::download(new OfficerReportExport($start_date, $end_date), Carbon::now()->toDateString() . '-officer-reports.xls');
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

        return Excel::download(new OfficerReportByUserExport($start_date, $end_date, $request->user_id), Carbon::now()->toDateString() . '-officer-reports-' . $user->name . '.xls');
    }

    public function exportPdf(Request $request)
    {
        // get request start_date and end_date or set default this month
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-t');

        $officerReports = User::with(['customerBillingFollowups' => function ($query) use ($start_date, $end_date) {
            $query->whereBetween('created_at', [$start_date, $end_date]);
        }, 'roles'])->whereHas('roles', function($query) {
            $query->where('name', 'Surveyor')->orWhere('name', 'Penagih');
        })->get();

        $pdf = Pdf::loadView('officer-reports.pdf', [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'data' => $officerReports,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('pdf');
    }
}
