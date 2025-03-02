<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProspectiveCustomer;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProspectiveCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view("prospective-customers.index");
    }

    // Fetch data for DataTable
    public function fetchDataTable(Request $request)
    {
        // load all users with their detail_users and roles
        $prospectiveCustomers = ProspectiveCustomer::with('user')->latest();

        return DataTables::of($prospectiveCustomers)
            ->addIndexColumn()
            ->addColumn('user', function ($prospectiveCustomer) {
                return $prospectiveCustomer->user->name ?? '-';
            })
            ->addColumn('action', function ($user) {
                return view('prospective-customers.action', ['value' => $user]);
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();

        return view("prospective-customers.create", [
            "users" => $users,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'no_ktp' => 'required|numeric|max:255|unique:prospective_customers,no_ktp',
            'bank' => 'required|string|max:255',
            'ktp' => 'required|file|mimes:jpg,png,jpeg|max:2048',
            'kk' => 'required|file|mimes:jpg,png,jpeg|max:2048',
            'user_id' => 'nullable|exists:users,id',
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

        return view('prospective-customers.edit', [
            'prospectiveCustomer' => $prospectiveCustomer,
            'users' => $users,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'no_ktp' => 'required|numeric|max:255|unique:prospective_customers,no_ktp,' . $prospectiveCustomer->id,
            'bank' => 'required|string|max:255',
            'ktp' => 'required|file|mimes:jpg,png,jpeg|max:2048',
            'kk' => 'required|file|mimes:jpg,png,jpeg|max:2048',
            'user_id' => 'nullable|exists:users,id',
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
}
