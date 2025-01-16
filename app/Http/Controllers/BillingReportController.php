<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use App\Models\Billing;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Exports\BillingReportExport;
use Maatwebsite\Excel\Facades\Excel;

class BillingReportController extends Controller implements HasMiddleware
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

        return view('billing-reports.index', [
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }

    public function fetchDataTable(Request $request)
    {
        // get request start_date and end_date or set default this month
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-t');

        $billings = Billing::with(['customer', 'user'])
            ->whereBetween('date', [$start_date, $end_date])
            ->where('status', 'pending')
            ->orWhere('status', 'process')
            ->orWhere('status', 'success')
            ->orWhere('status', 'cancel')
            ->orderBy('status', 'asc')
            ->get();

        return DataTables::of($billings)
            ->addIndexColumn()
            ->addColumn('customer', function ($billing) {
                return $billing->customer->name_customer;
            })
            ->addColumn('user', function ($billing) {
                return $billing->user->name ?? '-';
            })
            ->editColumn('date', function ($billing) {
                return Carbon::parse($billing->date)->format('d-m-Y');
            })
            ->editColumn('status', function ($billing) {
                return '<span class="badge badge-' . $billing->status->color() . '">' . $billing->status->label() . '</span>';
            })
            ->addColumn('billingStatuses.status', function ($billing) {
                return optional($billing->billingStatuses->last())->status ? '<span class="badge badge-' . $billing->billingStatuses->last()->status->color() . '">' . $billing->billingStatuses->last()->status->label() . '</span>' : '-';
            })
            ->addColumn('billingStatuses.promise_date', function ($billing) {
                return optional($billing->billingStatuses->last())->promise_date ? Carbon::parse($billing->billingStatuses->last()->promise_date)->format('d-m-Y') : '-';
            })
            ->addColumn('billingStatuses.payment_amount', function ($billing) {
                // return optional($billing->billingStatuses->last())->payment_amount ? number_format($billing->billingStatuses->last()->payment_amount, 0, ',', '.') : '-';
                return optional($billing->billingStatuses->last())->payment_amount ? 'Rp ' . number_format($billing->billingStatuses->last()->payment_amount, 0, ',', '.') : '-';
            })
            ->addColumn('billingStatuses.evidence', function ($billing) {
                return optional($billing->billingStatuses->last())->evidence ? '<a href="' . asset('images/billings/' . $billing->billingStatuses->last()->evidence) . '" target="_blank">Lihat</a>' : '-';
            })
            ->addColumn('billingStatuses.description', function ($billing) {
                return optional($billing->billingStatuses->last())->description ?? '-';
            })
            ->addColumn('billingStatuses.signature_officer', function ($billing) {
                return optional($billing->billingStatuses->last())->signature_officer ? '<a href="' . asset('images/billings/' . $billing->billingStatuses->last()->signature_officer) . '" target="_blank">Lihat</a>' : '-';
            })
            ->addColumn('billingStatuses.signature_customer', function ($billing) {
                return optional($billing->billingStatuses->last())->signature_customer ? '<a href="' . asset('images/billings/' . $billing->billingStatuses->last()->signature_customer) . '" target="_blank">Lihat</a>' : '-';
            })
            ->addColumn('action', function ($billing) {
                return view('billing-reports.action', ['value' => $billing]);
            })
            ->addColumn('details', function ($billing) {
                return;
            })
            ->rawColumns(['select', 'status', 'billingStatuses.status', 'billingStatuses.evidence', 'billingStatuses.signature_officer', 'billingStatuses.signature_customer', 'action'])
            ->toJson();
    }

    public function export(Request $request)
    {
        // get request start_date and end_date or set default this month
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-t');

        return Excel::download(new BillingReportExport($start_date, $end_date), Carbon::now()->toDateString() . '-billing-reports.xls');
    }
}
