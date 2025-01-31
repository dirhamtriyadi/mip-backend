<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Billing;
use Barryvdh\DomPDF\Facade\Pdf;

class BillingReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(string $id)
    {
        //
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

    public function exportPdfByUser(Request $request)
    {
        $user = auth()->user();
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-t');

        $billings = Billing::with(['customer', 'user', 'latestBillingStatus'])
            ->whereBetween('date', [$start_date, $end_date])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

            // dd($billings->toArray());

        $pdf = Pdf::loadView('templates.pdf.billing-reports-pdf', [
            'user' => $user,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'data' => $billings,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('pdf');
    }

    public function exportPdfByCustomer(Request $request)
    {
        $user = auth()->user();
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-t');

        $billings = Billing::with(['customer', 'user', 'latestBillingStatus'])
            ->whereBetween('date', [$start_date, $end_date])
            ->where('user_id', $user->id)
            ->where('customer_id', $request->customer_id)
            ->latest()
            ->get();

            dd($billings->toArray());
    }
}
