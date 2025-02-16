<?php

namespace App\Exports;

use App\Models\CustomerBilling;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class CustomerBillingExport implements FromView
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
        $billings = CustomerBilling::with(['customer', 'user'])->whereBetween('created_at', [$this->start_date, $this->end_date])->get();

        return view('customer-billing-reports.excel', [
            'data' => $billings
        ]);
    }
}
