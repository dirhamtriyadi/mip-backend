<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class ProspectiveCustomerSurveyReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // get request start_date and end_date or set default this month
        $start_date = $request->start_date ?? date('Y-01-01');
        $end_date = $request->end_date ?? date('Y-m-t');

        return view('prospective-customer-survey-reports.index', [
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

        // get all users with prospectiveCustomerSurveys between start_date and end_date with deleted_at and deteled_by is null
        $users = User::with(['prospectiveCustomerSurveys' => function ($query) use ($start_date, $end_date) {
            $query->whereBetween('created_at', [$start_date, $end_date]);
        }, 'roles'])->whereHas('roles', function($query) {
            $query->where('name', 'Surveyor')->orWhere('name', 'Penagih');
        })->get();

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('name', function ($user) {
                return $user->name;
            })
            ->addColumn('total_surveys', function ($user) {
                return '<span class="badge badge-info">' . $user->prospectiveCustomerSurveys()->count() . '</span>';
            })
            ->addColumn('total_pending_surveys', function ($user) {
                return '<span class="badge badge-warning">' . $user->prospectiveCustomerSurveys()
                    ->where('status', 'pending')
                    ->count() . '</span>';
            })
            ->addColumn('total_done_surveys', function ($user) {
                return '<span class="badge badge-success">' . $user->prospectiveCustomerSurveys()
                    ->where('status', 'done')
                    ->count() . '</span>';
            })
            ->addColumn('action', function ($user) use ($start_date, $end_date) {
                return view('prospective-customer-survey-reports.action', [
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'value' => $user,
                ]);
            })
            ->rawColumns(['total_surveys', 'total_pending_surveys', 'total_done_surveys', 'action'])
            ->toJson();
    }

    public function fetchDataTableByOfficerSurvey(Request $request)
    {
        // Ambil start_date dan end_date dari request atau set default ke bulan ini
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-t');
        $id = $request->id;

        // Query user dengan filtering role dan customerBillingFollowups
        $officerReport = User::with([
            'prospectiveCustomerSurveys',
            'roles'
        ])
        ->whereHas('roles', function ($query) {
            $query->whereIn('name', ['Surveyor', 'Penagih']);
        })
        ->findOrFail($id); // Ambil user berdasarkan ID

        return DataTables::of($officerReport->prospectiveCustomerSurveys)
            ->addIndexColumn()
            ->editColumn('updated_at', function ($data) {
                return $data->updated_at ? Carbon::parse($data->updated_at)->format('d/m/Y') : '-';
            })
            ->addColumn('name_customer', function ($data) {
                return $data->name ?? '-';
            })
            ->editColumn('status', function ($data) {
                return $data->status ? '<span class="badge badge-' . $data->status->color() . '">' . $data->status->label() . '</span>' : '-';
            })
            ->rawColumns(['status'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-t');

        $officerReport = User::with(['prospectiveCustomerSurveys' => function ($query) use ($start_date, $end_date) {
            $query->whereBetween('created_at', [$start_date, $end_date]);
        }, 'roles'])->whereHas('roles', function($query) {
            $query->where('name', 'Surveyor')->orWhere('name', 'Penagih');
        })->find($id);

        return view('prospective-customer-survey-reports.show', [
            'officerReport' => $officerReport,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
