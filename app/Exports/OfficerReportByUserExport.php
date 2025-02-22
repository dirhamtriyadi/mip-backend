<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class OfficerReportByUserExport implements FromView
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

        $officerReportsByUser = User::with(['customerBillingFollowups' => function ($query) use ($start_date, $end_date) {
            $query->whereBetween('created_at', [$start_date, $end_date]);
        }, 'roles'])->whereHas('roles', function($query) {
            $query->where('name', 'Surveyor')->orWhere('name', 'Penagih');
        })->where('id', $user_id)->first();

        return view('officer-reports.excel-by-user', [
            'data' => $officerReportsByUser
        ]);
    }
}
