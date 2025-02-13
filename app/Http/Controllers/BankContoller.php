<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use App\Models\Bank;
use Yajra\DataTables\Facades\DataTables;

class BankContoller extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            // 'auth',
            // new Middleware('subscribed', except: ['store']),
            new Middleware('permission:bank.index', only: ['index']),
            new Middleware('permission:bank.create', only: ['index', 'create', 'store']),
            new Middleware('permission:bank.edit', only: ['index', 'edit', 'update']),
            new Middleware('permission:bank.delete', only: ['index', 'destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('banks.index');
    }

    // Fetch data for DataTable
    public function fetchDataTable(Request $request)
    {
        // load all bank
        $banks = Bank::all();

        return DataTables::of($banks)
            ->addIndexColumn()
            ->editColumn('id', function ($bank) {
                return $bank->id;
            })
            ->addColumn('name', function ($bank) {
                return $bank->name;
            })
            ->addColumn('total_customer', function ($bank) {
                return '<a href="' . route('customers.index') . '">' . $bank->customers()->count() . '</a>';
            })
            ->addColumn('action', function ($customer) {
                return view('banks.action', ['value' => $customer]);
            })
            ->rawColumns(['total_customer', 'action'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('banks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:banks',
            'branch_code' => 'nullable|numeric|unique:banks'
        ]);

        $bank = Bank::create($validatedData);

        return redirect()->route('banks.index')->with('success', 'Bank berhasil dibuat');
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
        $bank = Bank::findOrFail($id);

        return view('banks.edit', [
            'data' => $bank,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:banks,name,'.$id,
            'branch_code' => 'nullable|numeric|unique:banks,branch_code,'.$id
        ]);

        $bank = Bank::findOrFail($id);

        $bank->update($validatedData);

        return redirect()->route('banks.index')->with('success', 'Bank berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bank = Bank::findOrFail($id);

        $bank->delete();

        return redirect()->route('banks.index')->with('success', 'Bank berhasil dihapus');
    }
}
