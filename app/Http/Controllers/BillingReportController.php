<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Billing;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Exports\BillingReportExport;
use Maatwebsite\Excel\Facades\Excel;

class BillingReportController extends Controller
{
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
            ->where('destination', 'visit')
            ->orWhere('destination', 'promise')
            ->orWhere('destination', 'pay')
            ->get();

        return DataTables::of($billings)
            ->addColumn('select', function ($billing) {
                return '<input type="checkbox" class="checkbox" id="select-' . $billing->id . '" name="checkbox[]" value="' . $billing->id . '">';
            })
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
            ->editColumn('destination', function ($billing) {
                return view('billings.destination', ['value' => $billing]);
            })
            ->editColumn('image_visit', function ($billing) {
                return $billing->image_visit ? '<a href="' . asset('images/billings/' . $billing->image_visit) . '" target="_blank">Lihat</a>' : '-';
            })
            ->editColumn('description_visit', function ($billing) {
                return $billing->description_visit ? $billing->description_visit : '-';
            })
            ->editColumn('promise_date', function ($billing) {
                return $billing->promise_date ? Carbon::parse($billing->promise_date)->format('d-m-Y') : '-';
            })
            ->editColumn('image_promise', function ($billing) {
                return $billing->image_promise ? '<a href="' . asset('images/billings/' . $billing->image_promise) . '" target="_blank">Lihat</a>' : '-';
            })
            ->editColumn('description_promise', function ($billing) {
                return $billing->description_promise ? $billing->description_promise : '-';
            })
            ->editColumn('amount', function ($billing) {
                return $billing->amount ? 'Rp ' . number_format($billing->amount, 0, ',', '.') : '-';
            })
            ->editColumn('image_amount', function ($billing) {
                return $billing->image_amount ? '<a href="' . asset('images/billings/' . $billing->image_amount) . '" target="_blank">Lihat</a>' : '-';
            })
            ->editColumn('description_amount', function ($billing) {
                return $billing->description_amount ? $billing->description_amount : '-';
            })
            ->editColumn('signature_officer', function ($billing) {
                return $billing->signature_officer ? '<a href="' . asset('images/billings/' . $billing->signature_officer) . '" target="_blank">Lihat</a>' : '-';
            })
            ->editColumn('signature_customer', function ($billing) {
                return $billing->signature_customer ? '<a href="' . asset('images/billings/' . $billing->signature_customer) . '" target="_blank">Lihat</a>' : '-';
            })
            ->addColumn('action', function ($billing) {
                return view('billings.action', ['value' => $billing]);
            })
            ->addColumn('details', function ($billing) {
                return;
            })
            ->rawColumns(['select', 'destination', 'image_visit', 'image_promise', 'image_amount', 'signature_officer', 'signature_customer', 'action'])
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
