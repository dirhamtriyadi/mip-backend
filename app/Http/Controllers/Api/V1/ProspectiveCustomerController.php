<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProspectiveCustomer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Validator;
use App\Http\Resources\Api\V1\ProspectiveCustomerResource;

class ProspectiveCustomerController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'no_ktp' => 'required|numeric|unique:prospective_customers,no_ktp',
            'bank' => 'required|string|max:255',
            'ktp' => 'required|file|mimes:jpg,png,jpeg|max:2048',
            'kk' => 'required|file|mimes:jpg,png,jpeg|max:2048',
            'user_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validatedData = $validator->validated();

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
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan',
                'data' => ProspectiveCustomerResource::collection(ProspectiveCustomer::all()),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack(); // Jika ada error, batalkan semua perubahan

            // Hapus file jika sudah terlanjur disimpan
            if (!empty($validatedData['ktp'])) {
                File::delete(public_path('images/prospective-customers/' . $validatedData['ktp']));
            }
            if (!empty($validatedData['kk'])) {
                File::delete(public_path('images/prospective-customers/' . $validatedData['kk']));
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan',
                'errors' => [
                    'message' => $e->getMessage(),
                ],
            ], 500);
        }
    }
}
