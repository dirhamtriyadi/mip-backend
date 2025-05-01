<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class OfficerReportExport implements FromView
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

        $officerReports = User::with(['customerBillingFollowups' => function ($query) use ($start_date, $end_date) {
            $query->whereBetween('created_at', [$start_date, $end_date]);
        }, 'prospectiveCustomerSurveys' => function ($query) use ($start_date, $end_date) {
            $query->whereBetween('created_at', [$start_date, $end_date]);
        }, 'roles'])->whereHas('roles', function($query) {
            $query->where('name', 'Surveyor')->orWhere('name', 'Penagih');
        })->get();

        return view('officer-reports.excel', [
            'data' => $officerReports
        ]);
    }
}
