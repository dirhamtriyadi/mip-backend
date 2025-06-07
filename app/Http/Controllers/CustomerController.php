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
use App\Helpers\LoggerHelper;
use Illuminate\Validation\ValidationException;

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
                return $customer->bank->name ?? '-';
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
            ->editColumn('due_date', function ($customer) {
                return $customer->due_date ? Carbon::parse($customer->due_date)->format('d-m-Y') : '-';
            })
            ->addColumn('action', function ($customer) {
                return view('customers.action', ['value' => $customer]);
            })
            ->addColumn('details', function ($customerBilling) {
                return;
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
        try {
            //code...
            $validatedData = $request->validate([
                'no_contract' => 'required|numeric|unique:customers',
                'bank_account_number' => 'nullable|numeric|unique:customers',
                'name_customer' => 'required',
                'name_mother' => 'nullable',
                'phone_number' => 'nullable',
                'status' => 'nullable|in:paid,not_yet_paid',
                'bank_id' => 'nullable|numeric',
                // 'user_id' => 'nullable|numeric',
                'margin_start' => 'nullable|numeric',
                'os_start' => 'nullable|numeric',
                'margin_remaining' => 'nullable|numeric',
                'installments' => 'nullable|numeric',
                'month_arrears' => 'nullable|numeric',
                'arrears' => 'nullable|numeric',
                'due_date' => 'nullable|date',
                'address' => 'nullable',
                'village' => 'nullable',
                'subdistrict' => 'nullable',
                'description' => 'nullable'
            ]);

            $validatedData['created_by'] = auth()->id();
            $customer = Customer::create([
                'no_contract' => $validatedData['no_contract'],
                'bank_account_number' => $validatedData['bank_account_number'],
                'name_customer' => $validatedData['name_customer'],
                'name_mother' => $validatedData['name_mother'],
                'phone_number' => $validatedData['phone_number'],
                'status' => $validatedData['status'] ?? null,
                'bank_id' => $validatedData['bank_id'],
                // 'user_id' => $validatedData['user_id'],
                'margin_start' => $validatedData['margin_start'],
                'os_start' => $validatedData['os_start'],
                'margin_remaining' => $validatedData['margin_remaining'],
                'installments' => $validatedData['installments'],
                'month_arrears' => $validatedData['month_arrears'],
                'arrears' => $validatedData['arrears'],
                'due_date' => $validatedData['due_date'],
                'description' => $validatedData['description']
            ]);
            $customer->customerAddress()->updateOrCreate(['customer_id' => $customer->id], [
                'address' => $validatedData['address'],
                'village' => $validatedData['village'],
                'subdistrict' => $validatedData['subdistrict']
            ]);

            return redirect()->route('customers.index')->with('success', 'Data berhasil disimpan');
        } catch (ValidationException $e) {
            LoggerHelper::logError($e);

            // Jika ada error validasi, kembalikan dengan pesan error
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Throwable $th) {
            //throw $th;
            LoggerHelper::logError($th);

            return redirect()->back()->with('errors', 'Gagal menyimpan data: ' . $th->getMessage())->withInput();
        }
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
        try {
            //code...
            $validatedData = $request->validate([
                'no_contract' => 'required|numeric|unique:customers,no_contract,' . $id,
                'bank_account_number' => 'nullable|numeric|unique:customers,bank_account_number,' . $id,
                'name_customer' => 'required',
                'name_mother' => 'nullable',
                'phone_number' => 'nullable',
                'status' => 'nullable|in:paid,not_yet_paid',
                'bank_id' => 'nullable|numeric',
                // 'user_id' => 'nullable|numeric',
                'margin_start' => 'nullable|numeric',
                'os_start' => 'nullable|numeric',
                'margin_remaining' => 'nullable|numeric',
                'installments' => 'nullable|numeric',
                'month_arrears' => 'nullable|numeric',
                'arrears' => 'nullable|numeric',
                'due_date' => 'nullable|date',
                'address' => 'nullable',
                'village' => 'nullable',
                'subdistrict' => 'nullable',
                'description' => 'nullable'
            ]);

            $validatedData['updated_by'] = auth()->id();

            $customer = Customer::findOrFail($id);
            $customer->update([
                'no_contract' => $validatedData['no_contract'],
                'bank_account_number' => $validatedData['bank_account_number'],
                'name_customer' => $validatedData['name_customer'],
                'name_mother' => $validatedData['name_mother'],
                'phone_number' => $validatedData['phone_number'],
                'status' => $validatedData['status'] ?? null,
                'bank_id' => $validatedData['bank_id'],
                // 'user_id' => $validatedData['user_id'],
                'margin_start' => $validatedData['margin_start'],
                'os_start' => $validatedData['os_start'],
                'margin_remaining' => $validatedData['margin_remaining'],
                'installments' => $validatedData['installments'],
                'month_arrears' => $validatedData['month_arrears'],
                'arrears' => $validatedData['arrears'],
                'due_date' => $validatedData['due_date'],
                'description' => $validatedData['description']
            ]);

            $customer->customerAddress()->updateOrCreate(['customer_id' => $customer->id], [
                'address' => $validatedData['address'],
                'village' => $validatedData['village'],
                'subdistrict' => $validatedData['subdistrict']
            ]);

            return redirect()->route('customers.index')->with('success', 'Data berhasil diperbarui');
        } catch (ValidationException $e) {
            LoggerHelper::logError($e);

            // Jika ada error validasi, kembalikan dengan pesan error
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Throwable $th) {
            //throw $th;
            LoggerHelper::logError($th);

            return redirect()->back()->with('errors', 'Gagal memperbarui data: ' . $th->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            //code...
            $customer = Customer::findOrFail($id);
            $customer->deleted_by = auth()->id();
            $customer->save();
            $customer->delete();

            return redirect()->route('customers.index')->with('success', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            //throw $th;
            LoggerHelper::logError($th);

            return redirect()->back()->with('errors', 'Gagal menghapus data: ' . $th->getMessage())->withInput();
        }
    }
}
