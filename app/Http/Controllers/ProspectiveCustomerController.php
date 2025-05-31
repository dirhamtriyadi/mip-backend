<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use App\Models\ProspectiveCustomer;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;
use App\Models\Bank;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProspectiveCustomerController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            // 'auth',
            // new Middleware('subscribed', except: ['store']),
            new Middleware('permission:calon-nasabah.index', only: ['index']),
            new Middleware('permission:calon-nasabah.create', only: ['index', 'create', 'store']),
            new Middleware('permission:calon-nasabah.edit', only: ['index', 'edit', 'update']),
            new Middleware('permission:calon-nasabah.delete', only: ['index', 'destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::all();

        $start_date = $request->start_date ?? date('Y-01-01');
        $end_date = $request->end_date ?? date('Y-m-t');

        return view('prospective-customers.index', [
            'users' => $users,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }

    // Fetch data for DataTable
    public function fetchDataTable(Request $request)
    {
        $start_date = $request->start_date ?? date('Y-01-01');
        $end_date = $request->end_date ?? date('Y-m-t');

        // load all users with their detail_users and roles
        $prospectiveCustomers = ProspectiveCustomer::with(['user', 'bank'])
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->latest();

        return DataTables::of($prospectiveCustomers)
            ->addIndexColumn()
            ->addColumn('user', function ($prospectiveCustomer) {
                return $prospectiveCustomer->user->name ?? '-';
            })
            ->addColumn('bank', function ($prospectiveCustomer) {
                return $prospectiveCustomer->bank->name ?? '-';
            })
            ->editColumn('ktp', function($prospectiveCustomer) {
                return $prospectiveCustomer->ktp ? '<a href="'.asset('images/prospective-customers/'.$prospectiveCustomer->ktp).'" target="_blank">Lihat</a>' : '-';
            })
            ->addColumn('kk', function($prospectiveCustomer) {
                return $prospectiveCustomer->kk ? '<a href="'.asset('images/prospective-customers/'.$prospectiveCustomer->kk).'" target="_blank">Lihat</a>' : '-';
            })
            ->addColumn('action', function ($user) {
                return view('prospective-customers.action', ['value' => $user]);
            })
            ->rawColumns(['ktp', 'kk', 'action'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $banks = Bank::all();

        return view("prospective-customers.create", [
            "users" => $users,
            "banks" => $banks,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'no_ktp' => 'required|numeric|unique:prospective_customers,no_ktp',
            'bank' => 'required|string|max:255',
            'ktp' => 'required|file|mimes:jpg,png,jpeg|max:2048',
            'kk' => 'required|file|mimes:jpg,png,jpeg|max:2048',
            'status' => 'nullable|in:pending,approved,rejected',
            'status_message' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
            'bank_id' => 'nullable|exists:banks,id',
        ]);

        DB::beginTransaction(); // Mulai transaksi database

        try {
            // Simpan file jika ada
            if ($request->hasFile('ktp')) {
                $file = $request->file('ktp');
                $fileName = $validatedData['name']. '-' . 'ktp' . '-' . time() . '.' . $file->getClientOriginalExtension();
                $path = public_path('images/prospective-customers/' . $fileName);

                $file->move(public_path('images/prospective-customers'), $fileName);
                $validatedData['ktp'] = $fileName;
            }
            if ($request->hasFile('kk')) {
                $file = $request->file('kk');
                $fileName = $validatedData['name'] . '-' . 'kk' . '-' . time() . '.' . $file->getClientOriginalExtension();
                $path = public_path('images/prospective-customers/' . $fileName);

                $file->move(public_path('images/prospective-customers'), $fileName);
                $validatedData['kk'] = $fileName;
            }

            // Simpan ke database
            ProspectiveCustomer::create($validatedData);

            DB::commit(); // Jika berhasil, simpan perubahan
            return redirect()->route('prospective-customers.index')->with('success', 'Prospective Customer created successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); // Jika ada error, batalkan semua perubahan

            // Hapus file jika sudah terlanjur disimpan
            if (!empty($validatedData['ktp'])) {
                File::delete(public_path('images/prospective-customers/' . $validatedData['ktp']));
            }
            if (!empty($validatedData['kk'])) {
                File::delete(public_path('images/prospective-customers/' . $validatedData['kk']));
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
        $prospectiveCustomer = ProspectiveCustomer::findOrFail($id);
        $users = User::all();
        $banks = Bank::all();

        return view('prospective-customers.edit', [
            'prospectiveCustomer' => $prospectiveCustomer,
            'users' => $users,
            'banks' => $banks,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'no_ktp' => 'required|numeric|unique:prospective_customers,no_ktp,' . $id,
            'ktp' => 'nullable|file|mimes:jpg,png,jpeg|max:2048',
            'kk' => 'nullable|file|mimes:jpg,png,jpeg|max:2048',
            'status' => 'nullable|in:approved,rejected',
            'status_message' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
            'bank_id' => 'nullable|exists:banks,id',
        ]);

        $prospectiveCustomer = ProspectiveCustomer::findOrFail($id);

        // save image check in
        if ($request->hasFile('ktp')) {
            // remove old image
            if (file_exists(public_path('images/prospective-customers/' . $prospectiveCustomer->ktp))) {
                unlink(public_path('images/prospective-customers/' . $prospectiveCustomer->ktp));
            }

            // save image to public/images/prospective-customers and change name file to name user-timestamp
            $file = $request->file('ktp');
            $fileName = $validatedData['name'] . '-' . 'ktp' . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/prospective-customers'), $fileName);
            $validatedData['ktp'] = $fileName;
        } else {
            unset($validatedData['ktp']);
        }

        // save image check out
        if ($request->hasFile('kk')) {
            // remove old image
            if (file_exists(public_path('images/prospective-customers/' . $prospectiveCustomer->kk))) {
                unlink(public_path('images/prospective-customers/' . $prospectiveCustomer->kk));
            }

            // save image to public/images/prospective-customers and change name file to name user-timestamp
            $file = $request->file('kk');
            $fileName = $validatedData['name'] . '-' . 'kk' . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/prospective-customers'), $fileName);
            $validatedData['kk'] = $fileName;
        } else {
            unset($validatedData['kk']);
        }

        $validatedData['updated_by'] = auth()->id();

        $prospectiveCustomer->update($validatedData);

        return redirect()->route('prospective-customers.index')->with('success', 'Prospective Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $prospectiveCustomer = ProspectiveCustomer::findOrFail($id);

        // remove image
        if (file_exists(public_path('images/prospective-customers/' . $prospectiveCustomer->ktp))) {
            unlink(public_path('images/prospective-customers/' . $prospectiveCustomer->ktp));
        }
        if (file_exists(public_path('images/prospective-customers/' . $prospectiveCustomer->kk))) {
            unlink(public_path('images/prospective-customers/' . $prospectiveCustomer->kk));
        }

        // $prospectiveCustomer->deleted_by = auth()->id();
        $prospectiveCustomer->save();
        $prospectiveCustomer->delete();

        return redirect()->route('prospective-customers.index')->with('success', 'Prospective Customer deleted successfully.');
    }

    public function proccessProspectiveCustomer(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:prospective_customers,id',
            'name' => 'required|string|max:255',
            'no_ktp' => 'required|numeric|unique:prospective_customers,no_ktp,' . $request->id,
            'address' => 'required|string',
            'address_status' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'npwp' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
            'status' => 'required|in:approved,rejected',
            'status_message' => 'nullable|string',
        ]);

        $prospectiveCustomer = ProspectiveCustomer::findOrFail($request->id);
        $prospectiveCustomer->status = $request->status;

        if ($request->status === 'approved') {
            // $prospectiveCustomer->status_message = null;
            $prospectiveCustomer->status_message = $request->status_message;
            $prospectiveCustomer->fill($validatedData);
        } else {
            $prospectiveCustomer->status_message = $request->status_message;
        }

        $prospectiveCustomer->save();

        if ($request->status === 'approved') {
            $prospectiveCustomer->prospectiveCustomerSurvey()->updateOrCreate(
                ['prospective_customer_id' => $prospectiveCustomer->id],
                [
                    'user_id' => $validatedData['user_id'] ?? null,
                    'status' => 'pending',
                    'name' => $validatedData['name'] ?? $prospectiveCustomer->name,
                    'address' => $validatedData['address'] ?? $prospectiveCustomer->address,
                    'number_ktp' => $validatedData['no_ktp'] ?? $prospectiveCustomer->no_ktp,
                    'address_status' => $validatedData['address_status'] ?? $prospectiveCustomer->address_status,
                    'phone_number' => $validatedData['phone_number'] ?? $prospectiveCustomer->phone_number,
                    'npwp' => $validatedData['npwp'] ?? $prospectiveCustomer->npwp,
                ]
            );
        }

        return redirect()->route('prospective-customers.index')
            ->with('success', 'Prospective Customer updated successfully.');
    }
}
