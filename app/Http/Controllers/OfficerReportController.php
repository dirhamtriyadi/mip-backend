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
        }, 'prospectiveCustomerSurveys', 'roles'])->whereHas('roles', function($query) {
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
            ->addColumn('surveys', function ($user) use ($start_date, $end_date) {
                $doneCount = $user->prospectiveCustomerSurveys()
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->where('status', 'done')
                    ->count();

                $pendingOrOngoingCount = $user->prospectiveCustomerSurveys()
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->where(function ($query) {
                        $query->where('status', 'pending')->orWhere('status', 'ongoing');
                    })
                    ->count();

                return 'Selesai: <a href="' . route('prospective-customer-surveys.index', ['user_id' => $user->id, 'start_date' => $start_date, 'end_date' => $end_date]) . '" class="btn btn-primary">' . $doneCount . '</a> <br/> Belum Selesai: <a href="' . route('prospective-customer-surveys.index', ['user_id' => $user->id, 'start_date' => $start_date, 'end_date' => $end_date]) . '" class="btn btn-danger">' . $pendingOrOngoingCount . '</a>';
            })
            ->addColumn('action', function ($user) use ($start_date, $end_date) {
                return view('officer-reports.action', [
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'value' => $user,
                ]);
            })
            ->rawColumns(['surveys', 'action'])
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
        // Ambil start_date dan end_date dari request atau set default ke bulan ini
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-t');
        $id = $request->id;

        // Query user dengan filtering role dan customerBillingFollowups
        $officerReport = User::with([
            'customerBillingFollowups' => function ($query) use ($start_date, $end_date) {
                $query->whereBetween('date_exec', [$start_date, $end_date])
                    ->whereHas('customerBilling.customer') // Pastikan customer ada
                    ->join('customer_billings', 'billing_followups.customer_billing_id', '=', 'customer_billings.id')
                    ->join('customers', 'customer_billings.customer_id', '=', 'customers.id')
                    ->orderBy('customers.name_customer', 'asc') // Urutkan berdasarkan nama customer
                    ->orderBy('billing_followups.date_exec', 'asc') // Urutkan berdasarkan date_exec
                    ->select('billing_followups.*'); // Pilih hanya kolom dari billing_followups untuk menghindari duplikasi data
            },
            'customerBillingFollowups.customerBilling.customer', // Pastikan customer di-load
            'roles'
        ])
        ->whereHas('roles', function ($query) {
            $query->whereIn('name', ['Surveyor', 'Penagih']);
        })
        ->findOrFail($id); // Ambil user berdasarkan ID

        return DataTables::of($officerReport->customerBillingFollowups)
            ->addIndexColumn()
            ->editColumn('date_exec', function ($data) {
                return $data->date_exec ? Carbon::parse($data->date_exec)->format('d/m/Y') : '-';
            })
            ->addColumn('name_customer', function ($data) {
                return $data->customerBilling->customer->name_customer ?? '-';
            })
            ->editColumn('status', function ($data) {
                return $data->status ? '<span class="badge badge-' . $data->status->color() . '">' . $data->status->label() . '</span>' : '-';
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
