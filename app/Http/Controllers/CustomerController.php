<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class CustomerController extends Controller
{
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
        $customers = Customer::all();

        return DataTables::of($customers)
            ->addIndexColumn()
            ->editColumn('date', function ($billing) {
                return Carbon::parse($billing->date)->format('d-m-Y');
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
        return view('customers.create');
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
            'name_bank' => 'nullable',
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

        return view('customers.edit', [
            'data' => $customer,
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
            'name_bank' => 'nullable',
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
