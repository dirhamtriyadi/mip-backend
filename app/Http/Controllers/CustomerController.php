<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use App\Models\Customer;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Bank;
use App\Models\User;
use Carbon\Carbon;

class CustomerController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            // 'auth',
            // new Middleware('subscribed', except: ['store']),
            new Middleware('permission:nasabah.index', only: ['index']),
            new Middleware('permission:nasabah.create', only: ['index', 'create', 'store']),
            new Middleware('permission:nasabah.edit', only: ['index', 'edit', 'update']),
            new Middleware('permission:nasabah.delete', only: ['index', 'destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('customers.index');
    }

    // Fetch data for DataTable
    public function fetchDataTable(Request $request)
    {
        // load all bank accounts
        // $customers = Customer::with(['bank', 'user', 'customerAddress'])->get();
        $customers = Customer::with(['bank', 'customerAddress'])->get();

        return DataTables::of($customers)
            ->addIndexColumn()
            ->addColumn('name_bank', function ($customer) {
                return $customer->bank->name;
            })
            ->addColumn('name_officer', function ($customer) {
                return $customer->user->name ?? '-';
            })
            ->addColumn('address', function ($customer) {
                return $customer->customerAddress->address ?? '-';
            })
            ->addColumn('village', function ($customer) {
                return $customer->customerAddress->village ?? '-';
            })
            ->addColumn('subdistrict', function ($customer) {
                return $customer->customerAddress->subdistrict ?? '-';
            })
            ->editColumn('date', function ($billing) {
                return Carbon::parse($billing->date)->format('d-m-Y');
            })
            ->editColumn('total_bill', function ($billing) {
                return $billing->total_bill ?? '0';
            })
            ->editColumn('installment', function ($billing) {
                return $billing->installment ?? '0';
            })
            ->addColumn('action', function ($customer) {
                return view('customers.action', ['value' => $customer]);
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $banks = Bank::all();
        // $users = User::all();

        return view('customers.create', [
            'banks' => $banks,
            // 'users' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'no_contract' => 'required|numeric|unique:customers',
            'bank_account_number' => 'nullable|numeric|unique:customers',
            'name_customer' => 'required',
            'name_mother' => 'nullable',
            'phone_number' => 'nullable',
            'status' => 'nullable|in:paid,not_yet_paid',
            'bank_id' => 'nullable|numeric',
            // 'user_id' => 'nullable|numeric',
            'os_start' => 'nullable|numeric',
            'os_remaining' => 'nullable|numeric',
            'os_total' => 'nullable|numeric',
            'monthly_installments' => 'nullable|numeric',
            'address' => 'nullable',
            'village' => 'nullable',
            'subdistrict' => 'nullable',
            'description' => 'nullable'
        ]);

        $validatedData['created_by'] = auth()->id();
        $customer = Customer::create($validatedData);
        $customer->customerAddress()->updateOrCreate(['customer_id' => $customer->id], [
            'address' => $validatedData['address'],
            'village' => $validatedData['village'],
            'subdistrict' => $validatedData['subdistrict']
        ]);

        return redirect()->route('customers.index')->with('success', 'Data berhasil disimpan');
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
        $customer = Customer::findOrFail($id);
        $banks = Bank::all();
        // $users = User::all();

        return view('customers.edit', [
            'customer' => $customer,
            'banks' => $banks,
            // 'users' => $users
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'no_contract' => 'required|numeric|unique:customers,no_contract,' . $id,
            'bank_account_number' => 'nullable|numeric|unique:customers,bank_account_number,' . $id,
            'name_customer' => 'required',
            'name_mother' => 'nullable',
            'phone_number' => 'nullable',
            'status' => 'nullable|in:paid,not_yet_paid',
            'bank_id' => 'nullable|numeric',
            // 'user_id' => 'nullable|numeric',
            'os_start' => 'nullable|numeric',
            'os_remaining' => 'nullable|numeric',
            'os_total' => 'nullable|numeric',
            'monthly_installments' => 'nullable|numeric',
            'address' => 'nullable',
            'village' => 'nullable',
            'subdistrict' => 'nullable',
            'description' => 'nullable'
        ]);

        $validatedData['updated_by'] = auth()->id();

        $customer = Customer::findOrFail($id);
        $customer->update($validatedData);

        $customer->customerAddress()->updateOrCreate(['customer_id' => $customer->id], [
            'address' => $validatedData['address'],
            'village' => $validatedData['village'],
            'subdistrict' => $validatedData['subdistrict']
        ]);

        return redirect()->route('customers.index')->with('success', 'Data berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->deleted_by = auth()->id();
        $customer->save();
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Data berhasil dihapus');
    }
}
