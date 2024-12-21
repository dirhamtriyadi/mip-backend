<?php

namespace App\Exports;

use App\Models\Billing;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class BillingReportExport implements FromView
{
    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    // public function startDate($start_date)
    // {
    //     $this->start_date = $start_date;

    //     return $this;
    // }

    // public function endDate($end_date)
    // {
    //     $this->end_date = $end_date;

    //     return $this;
    // }

    public function view(): View
    {
        $billings = Billing::with(['customer', 'user'])->whereBetween('date', [$this->start_date, $this->end_date])->get();

        return view('billing-reports.excel', [
            'data' => $billings
        ]);
    }
}
