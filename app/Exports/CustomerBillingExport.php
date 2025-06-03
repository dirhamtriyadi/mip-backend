<?php

namespace App\Exports;

use App\Models\CustomerBilling;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\Auth;

class CustomerBillingExport implements FromView
{
    protected $start_date;
    protected $end_date;
    protected $user;

    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->user = Auth::user(); // Ambil data user yang sedang login
    }

    public function view(): View
    {
        // Query dasar dengan tanggal
        $customerBilling = CustomerBilling::with(['customer', 'user', 'latestBillingFollowups' => function ($query) use ($start_date, $end_date) {
                $query->whereDate('created_at', '>=', $start_date)
                      ->whereDate('created_at', '<=', $end_date);
            }]);
            // ->whereBetween('created_at', [$this->start_date, $this->end_date]);

        // Terapkan filter berdasarkan izin pengguna
        if (!$this->user->hasPermissionTo('laporan-penagihan.all-data')) {
            if ($this->user->hasPermissionTo('laporan-penagihan.data-my-bank')) {
                // Filter berdasarkan bank_id
                $customerBilling->whereHas('customer', function ($query) {
                    $query->where('bank_id', $this->user->bank_id);
                });
            } else {
                // Jika tidak punya izin melihat semua data atau data bank, hanya tampilkan data miliknya
                $customerBilling->where('user_id', $this->user->id);
            }
        }

        // Eksekusi query setelah semua filter diterapkan
        $customerBillings = $customerBilling->get();

        return view('customer-billing-reports.excel', [
            'data' => $customerBillings
        ]);
    }
}
