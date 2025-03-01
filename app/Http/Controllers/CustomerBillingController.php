<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\CustomerBilling;
use App\Models\User;
use App\Models\Customer;
use App\Models\Bank;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CustomerBillingImport;

class CustomerBillingController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            // 'auth',
            // new Middleware('subscribed', except: ['store']),
            new Middleware('permission:penagihan.index', only: ['index']),
            new Middleware('permission:penagihan.create', only: ['index', 'create', 'store']),
            new Middleware('permission:penagihan.edit', only: ['index', 'edit', 'update']),
            new Middleware('permission:penagihan.delete', only: ['index', 'destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        $banks = Bank::all();

        return view('customer-billings.index', [
            'users' => $users,
            'banks' => $banks,
        ]);
    }

    // Fetch data for DataTable
    public function fetchDataTable(Request $request)
    {
        // load all billings priority user_id is null and destination is visit
        $customerBilling = CustomerBilling::with(['user', 'customer.bank', 'latestBillingFollowups'])
            ->orderByRaw('CASE WHEN user_id IS NULL THEN 0 ELSE 1 END')
            ->get();
            // dd($customerBilling);

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
            return optional($customerBilling->latestBillingFollowups->first())->status ? '<span class="badge badge-' . $customerBilling->latestBillingFollowups->first()->status->color() . '">' . $customerBilling->latestBillingFollowups->first()->status->label() . '</span>' : '-';
        })
        ->addColumn('date_exec', function ($customerBilling) {
            return optional($customerBilling->latestBillingFollowups->first())->date_exec ? Carbon::parse($customerBilling->latestBillingFollowups->first()->date_exec)->format('d-m-Y') : '-';
        })
        ->addColumn('promise_date', function ($customerBilling) {
            return optional($customerBilling->latestBillingFollowups->first())->promise_date ? Carbon::parse($customerBilling->latestBillingFollowups->first()->promise_date)->format('d-m-Y') : '-';
        })
        ->addColumn('payment_amount', function ($customerBilling) {
            return optional($customerBilling->latestBillingFollowups->first())->payment_amount ?  'Rp ' . number_format($customerBilling->latestBillingFollowups->first()->payment_amount, 0, ',', '.') : '-';
        })
        ->addColumn('proof', function ($customerBilling) {
            return optional($customerBilling->latestBillingFollowups->first())->proof ? '<a href="' . asset('images/customer-billings/' . $customerBilling->latestBillingFollowups->first()->proof) . '" target="_blank">Lihat</a>' : '-';
        })
        ->addColumn('description', function ($customerBilling) {
            return optional($customerBilling->latestBillingFollowups->first())->description ?? '-';
        })
        ->addColumn('signature_officer', function ($customerBilling) {
            return optional($customerBilling->latestBillingFollowups->first())->signature_officer ? '<a href="' . asset('images/customer-billings/' . $customerBilling->latestBillingFollowups->first()->signature_officer) . '" target="_blank">Lihat</a>' : '-';
        })
        ->addColumn('signature_customer', function ($customerBilling) {
            return optional($customerBilling->latestBillingFollowups->first())->signature_customer ? '<a href="' . asset('images/customer-billings/' . $customerBilling->latestBillingFollowups->first()->signature_customer) . '" target="_blank">Lihat</a>' : '-';
        })
        ->addColumn('action', function ($customerBilling) {
            return view('customer-billings.action', ['value' => $customerBilling]);
        })
        ->addColumn('details', function ($customerBilling) {
            return;
        })
        ->rawColumns(['select', 'status', 'proof', 'signature_officer', 'signature_customer', 'action'])
        ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $customers = Customer::all();

        return view('customer-billings.create', [
            'users' => $users,
            'customers' => $customers
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'bill_number' => 'nullable|unique:billings',
            // 'date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'nullable|exists:users,id',
            // 'status' => 'nullable|in:pending,process,success,cancel',
        ]);

        $customer = Customer::findOrFail($validatedData['customer_id']);
        if ($validatedData['bill_number'] === null) {
            $datePrefix = Carbon::now()->format('Ymd'); // YYYYMMDD
            $lastBill = CustomerBilling::where('bill_number', 'like', "$datePrefix%")
                ->latest('bill_number')
                ->first();

            if ($lastBill) {
                // Ambil nomor terakhir dan tambahkan 1
                $lastNumber = (int) substr($lastBill->bill_number, -4);
                $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            } else {
                // Jika belum ada, mulai dari 0001
                $nextNumber = '0001';
            }

            $validatedData['bill_number'] = $datePrefix . $nextNumber;
        }
        $validatedData['created_by'] = auth()->id();

        CustomerBilling::create($validatedData);

        return redirect()->route('customer-billings.index')->with('success', 'Data berhasil disimpan');
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
        $customerBilling = CustomerBilling::findOrFail($id);
        $customers = Customer::all();
        $users = User::all();

        return view('customer-billings.edit', [
            'data' => $customerBilling,
            'customers' => $customers,
            'users' => $users
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'bill_number' => 'nullable|unique:customer_billings,bill_number,' . $id,
            // 'date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'nullable|exists:users,id',
            // 'status' => 'required|in:pending,process,success,cancel',
        ]);

        $customerBilling = CustomerBilling::findOrFail($id);

        $customer = Customer::findOrFail($validatedData['customer_id']);
        if ($validatedData['bill_number'] === null) {
            $validatedData['bill_number'] = Carbon::now()->format('YmdHis') . $customer->no;
        }
        $validatedData['updated_by'] = auth()->id();

        $customerBilling->update($validatedData);

        return redirect()->route('customer-billings.index')->with('success', 'Data berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customerBilling = CustomerBilling::findOrFail($id);
        // $customerBilling->deleted_by = auth()->id();
        $customerBilling->save();
        $customerBilling->delete();

        return redirect()->route('customer-billings.index')->with('success', 'Data berhasil dihapus');
    }

    public function import(Request $request)
    {
        $validatedData = $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
            'bank_id' => 'required|numeric',
        ]);

        $file = $request->file('file');
        Excel::import(new CustomerBillingImport($validatedData['bank_id']), $file);

        return redirect()->route('customer-billings.index')->with('success', 'Data berhasil diimport');
    }

    public function templateImport()
    {
        $template = public_path('templates/customer-billing-template-import.xlsx');

        return response()->download($template);
    }


    public function massDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|exists:customer_billings,id',
        ]);

        $ids = $request->input('ids', []);
        $user = auth()->user();

        foreach ($ids as $id) {
            $customerBilling = CustomerBilling::findOrFail($id);
            // $customerBilling->deleted_by = $user->id;
            $customerBilling->save();
            $customerBilling->delete();
        }

        return redirect()->route('customer-billings.index')->with('success', 'Data berhasil dihapus');
    }

    public function massSelectOfficer(Request $request)
    {
        $validatedData = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|exists:customer_billings,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $ids = $request->input('ids', []);
        $user = auth()->user();

        foreach ($ids as $id) {
            $customerBilling = CustomerBilling::findOrFail($id);
            $customerBilling->user_id = $validatedData['user_id'];
            $customerBilling->updated_by = $user->id;
            $customerBilling->save();
        }

        return redirect()->route('customer-billings.index')->with('success', 'Data berhasil ditandatangani');
    }
}
