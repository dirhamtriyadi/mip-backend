<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use App\Models\Customer;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Bank;
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
        $customers = Customer::with('bank')->get();

        return DataTables::of($customers)
            ->addIndexColumn()
            ->addColumn('name_bank', function ($customer) {
                return $customer->bank->name;
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

        return view('customers.create', [
            'banks' => $banks,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'no' => 'required|numeric|unique:customers',
            'name_customer' => 'required',
            'phone_number' => 'nullable',
            'address' => 'required',
            'bank_id' => 'required|numeric',
            'date' => 'required|date',
            'total_bill' => 'nullable|numeric',
            'installment' => 'nullable|numeric',
            // 'remaining_installment' => 'required|numeric',
        ]);

        $validatedData['created_by'] = auth()->id();
        Customer::create($validatedData);

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

        return view('customers.edit', [
            'data' => $customer,
            'banks' => $banks,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'no' => 'required|numeric|unique:customers,no,' . $id,
            'name_customer' => 'required',
            'phone_number' => 'nullable',
            'address' => 'required',
            'bank_id' => 'required|numeric',
            'date' => 'required|date',
            'total_bill' => 'nullable|numeric',
            'installment' => 'nullable|numeric',
            // 'remaining_installment' => 'required|numeric',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->fill($validatedData);
        $customer->updated_by = auth()->id();
        $customer->save();

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
