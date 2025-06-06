<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\ProspectiveCustomerSurvey;
use App\Http\Resources\Api\V1\ProspectiveCustomerSurveyResource;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ProspectiveCustomerSurveyController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Ambil parameter pencarian
        $search = $request->search;

        // Validasi start_date & end_date
        try {
            $start_date = $request->filled('start_date')
                ? Carbon::parse($request->start_date)->startOfDay()
                : Carbon::now()->startOfMonth()->startOfDay();

            $end_date = $request->filled('end_date')
                ? Carbon::parse($request->end_date)->endOfDay()
                : Carbon::now()->endOfDay();
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid date format',
            ], 400);
        }

        // Query dengan filter user, tanggal, dan pencarian (jika ada)
        $surveys = ProspectiveCustomerSurvey::query()
            ->with(['user', 'prospectiveCustomer'])
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [$start_date, $end_date]);

        // Perbaikan logika pencarian agar OR tidak mempengaruhi query utama
        if (!empty($search)) {
            $surveys->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('number_ktp', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('address_status', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'data' => ProspectiveCustomerSurveyResource::collection($surveys->get())
        ]);
    }

    public function show(Request $request, string $id)
    {
        $survey = ProspectiveCustomerSurvey::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'data' => ProspectiveCustomerSurveyResource::make($survey)
        ]);
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'nullable|in:pending,ongoing,done',
            'user_id' => 'nullable|exists:users,id',
            'name' => 'nullable|string',
            'address' => 'nullable|string',
            'number_ktp' => 'nullable|string|unique:prospective_customer_surveys,number_ktp,' . $id,
            'address_status' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'npwp' => 'nullable|string',
            'job_type' => 'nullable|string',
            'company_name' => 'nullable|string',
            'job_level' => 'nullable|string',
            'employee_tenure' => 'nullable|string',
            'employee_status' => 'nullable|string',
            'salary' => 'nullable|string',
            'other_business' => 'nullable|string',
            'monthly_living_expenses' => 'nullable|string',
            'children' => 'nullable|string',
            'wife' => 'nullable|string',
            'couple_jobs' => 'nullable|string',
            'couple_business' => 'nullable|string',
            'couple_income' => 'nullable|string',
            'bank_debt' => 'nullable|string',
            'cooperative_debt' => 'nullable|string',
            'personal_debt' => 'nullable|string',
            'online_debt' => 'nullable|string',
            'customer_character_analysis' => 'nullable|string',
            'financial_report_analysis' => 'nullable|string',
            'slik_result' => 'nullable|string',
            'info_provider_name' => 'nullable|string',
            'info_provider_position' => 'nullable|string',
            'workplace_condition' => 'nullable|string',
            'employee_count' => 'nullable|string',
            'business_duration' => 'nullable|string',
            'office_address' => 'nullable|string',
            'office_phone' => 'nullable|string',
            'loan_application' => 'nullable|string',
            'recommendation_from_vendors' => 'nullable|string',
            'recommendation_from_treasurer' => 'nullable|string',
            'recommendation_from_other' => 'nullable|string',
            'recommendation_pt' => 'nullable|in:yes,no',
            'description_survey' => 'nullable|string',
            'location_survey' => 'nullable|string',
            'date_survey' => 'nullable|date',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'location_string' => 'nullable|string',
            'signature_officer' => 'nullable|image',
            'signature_customer' => 'nullable|image',
            'signature_couple' => 'nullable|image',
            'workplace_image1' => 'nullable|image',
            'workplace_image2' => 'nullable|image',
            'customer_image' => 'nullable|image',
            'ktp_image' => 'nullable|image',
            'loan_guarantee_image1' => 'nullable|image',
            'loan_guarantee_image2' => 'nullable|image',
            'kk_image' => 'nullable|image',
            'id_card_image' => 'nullable|image',
            'salary_slip_image1' => 'nullable|image',
            'salary_slip_image2' => 'nullable|image',
        ]);

        // Daftar field gambar
        $imageFields = [
            'signature_officer', 'signature_customer', 'signature_couple',
            'workplace_image1', 'workplace_image2', 'customer_image',
            'ktp_image', 'loan_guarantee_image1', 'loan_guarantee_image2',
            'kk_image', 'id_card_image', 'salary_slip_image1', 'salary_slip_image2'
        ];

        // Simpan file yang diunggah untuk rollback jika ada error
        $uploadedImages = [];
        $oldImages = [];

        DB::beginTransaction();
        try {
            $validatedData = $validator->validate();

            $prospectiveCustomerSurvey = ProspectiveCustomerSurvey::findOrFail($id);

            foreach ($imageFields as $field) {
                if ($request->hasFile($field)) {
                    // Simpan file lama untuk dihapus jika upload sukses
                    if ($prospectiveCustomerSurvey->$field) {
                        $oldImages[$field] = $prospectiveCustomerSurvey->$field;
                    }
                    // Simpan file baru
                    $path = $request->file($field)->store('survey', 'public');
                    $validatedData[$field] = $path;
                    $uploadedImages[$field] = $path;
                }
            }

            $prospectiveCustomerSurvey->update($validatedData);

            // Hapus file lama jika update berhasil
            foreach ($oldImages as $oldPath) {
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data updated successfully',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            // Hapus file baru yang diunggah jika terjadi error
            foreach ($uploadedImages as $newPath) {
                if (Storage::disk('public')->exists($newPath)) {
                    Storage::disk('public')->delete($newPath);
                }
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan',
                'errors' => [
                    'message' => $th->getMessage(),
                ],
            ], 500);
        }
    }

    public function exportPdfByCustomer(string $id)
    {
        $survey = ProspectiveCustomerSurvey::findOrFail($id);

        $pdf = Pdf::loadView('prospective-customer-surveys.pdf-by-customer', [
            'data' => $survey,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('pdf');
    }

    public function updateStatus(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,ongoing,done',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $survey = ProspectiveCustomerSurvey::findOrFail($id);
            $survey->update($request->only('status'));

            return response()->json([
                'status' => 'success',
                'message' => 'Status updated successfully',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating status',
                'errors' => [
                    'message' => $th->getMessage(),
                ],
            ], 500);
        }

    }
}
