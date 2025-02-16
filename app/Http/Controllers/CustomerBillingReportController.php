<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\CustomerBilling;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Exports\CustomerBillingExport;
use Maatwebsite\Excel\Facades\Excel;

class CustomerBillingReportController extends Controller
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            // 'auth',
            // new Middleware('subscribed', except: ['store']),
            new Middleware('permission:laporan-penagihan.index', only: ['index']),
            new Middleware('permission:laporan-penagihan.create', only: ['index', 'create', 'store']),
            new Middleware('permission:laporan-penagihan.edit', only: ['index', 'edit', 'update']),
            new Middleware('permission:laporan-penagihan.delete', only: ['index', 'destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // get request start_date and end_date or set default this month
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-t');

        return view('customer-billing-reports.index', [
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }

    public function fetchDataTable(Request $request)
    {
        // get request start_date and end_date or set default this month
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-t');

        $billings = CustomerBilling::with(['customer', 'user'])
            ->get();

            return DataTables::of($customerBilling)
            ->addColumn('select', function ($customerBilling) {
                return '<input type="checkbox" class="checkbox" id="select-' . $customerBilling->id . '" name="checkbox[]" value="' . $customerBilling->id . '">';
            })
            ->addIndexColumn()
            ->addColumn('no_contract', function ($customerBilling) {
                return optional($customerBilling->customer)->no_contract ?? '-';
            })
            ->addColumn('customer', function ($customerBilling) {
                return optional($customerBilling->customer)->name_customer ?? '-';
            })
            ->addColumn('user', function ($customerBilling) {
                return optional($customerBilling->user)->name ?? '-';
            })
            ->addColumn('bank', function ($customerBilling) {
                return optional($customerBilling->customer->bank)->name ?? '-';
            })
            ->editColumn('status', function ($customerBilling) {
                return optional($customerBilling->latestBillingFollowups->last())->status ? '<span class="badge badge-' . $customerBilling->latestBillingFollowups->last()->status->color() . '">' . $customerBilling->latestBillingFollowups->last()->status->label() . '</span>' : '-';
            })
            ->addColumn('date_exec', function ($customerBilling) {
                return optional($customerBilling->latestBillingFollowups->last())->date_exec ? Carbon::parse($customerBilling->latestBillingFollowups->last()->date_exec)->format('d-m-Y') : '-';
            })
            ->addColumn('promise_date', function ($customerBilling) {
                return optional($customerBilling->latestBillingFollowups->last())->promise_date ? Carbon::parse($customerBilling->latestBillingFollowups->last()->promise_date)->format('d-m-Y') : '-';
            })
            ->addColumn('payment_amount', function ($customerBilling) {
                return optional($customerBilling->latestBillingFollowups->last())->payment_amount ?  'Rp ' . number_format($customerBilling->latestBillingFollowups->last()->payment_amount, 0, ',', '.') : '-';
            })
            ->addColumn('proof', function ($customerBilling) {
                return optional($customerBilling->latestBillingFollowups->last())->proof ? '<a href="' . asset('images/customer-billings/' . $customerBilling->latestBillingFollowups->last()->proof) . '" target="_blank">Lihat</a>' : '-';
            })
            ->addColumn('description', function ($customerBilling) {
                return optional($customerBilling->latestBillingFollowups->last())->description ?? '-';
            })
            ->addColumn('signature_officer', function ($customerBilling) {
                return optional($customerBilling->latestBillingFollowups->last())->signature_officer ? '<a href="' . asset('images/customer-billings/' . $customerBilling->latestBillingFollowups->last()->signature_officer) . '" target="_blank">Lihat</a>' : '-';
            })
            ->addColumn('signature_customer', function ($customerBilling) {
                return optional($customerBilling->latestBillingFollowups->last())->signature_customer ? '<a href="' . asset('images/customer-billings/' . $customerBilling->latestBillingFollowups->last()->signature_customer) . '" target="_blank">Lihat</a>' : '-';
            })
            ->addColumn('details', function ($customerBilling) {
                return;
            })
            ->rawColumns(['select', 'status', 'proof', 'signature_officer', 'signature_customer'])
            ->toJson();
    }

    /**
     * Export the specified resource from storage.
     */
    public function export(Request $request)
    {
        // get request start_date and end_date or set default this month
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-t');

        return Excel::download(new CustomerBillingExport($start_date, $end_date), Carbon::now()->toDateString() . '-billing-reports.xls');
    }
}
