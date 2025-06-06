<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ProspectiveCustomerSurvey;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ProspectiveCustomerSurveyController extends Controller
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            // 'auth',
            // new Middleware('subscribed', except: ['store']),
            new Middleware('permission:survei.index', only: ['index']),
            new Middleware('permission:survei.create', only: ['index', 'create', 'store']),
            new Middleware('permission:survei.edit', only: ['index', 'edit', 'update']),
            new Middleware('permission:survei.delete', only: ['index', 'destroy']),
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

        return view('prospective-customer-surveys.index', [
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

        // load all billings priority user_id is null and destination is visit
        $prospectiveCustomerSurvey = ProspectiveCustomerSurvey::with(['user'])
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->orderByRaw('CASE WHEN user_id IS NULL THEN 0 ELSE 1 END')
            ->get();

        return DataTables::of($prospectiveCustomerSurvey)
        ->addColumn('select', function ($prospectiveCustomerSurvey) {
            return '<input type="checkbox" class="checkbox" id="select-' . $prospectiveCustomerSurvey->id . '" name="checkbox[]" value="' . $prospectiveCustomerSurvey->id . '">';
        })
        ->addIndexColumn()
        ->addColumn('user', function ($prospectiveCustomerSurvey) {
            return optional($prospectiveCustomerSurvey->user)->name ?? '-';
        })
        ->editColumn('status', function ($prospectiveCustomerSurvey) {
            return $prospectiveCustomerSurvey->status ? '<span class="badge badge-' . $prospectiveCustomerSurvey->status->color() . '">' . $prospectiveCustomerSurvey->status->label() . '</span>' : '-';
        })
        ->editColumn('signature_officer', function ($prospectiveCustomerSurvey) {
            return '<img src="' . asset('storage/' . $prospectiveCustomerSurvey->signature_officer) . '" alt="signature_officer" style="width: 100px; height: 100px;">';
        })
        ->editColumn('signature_customer', function ($prospectiveCustomerSurvey) {
            return '<img src="' . asset('storage/' . $prospectiveCustomerSurvey->signature_customer) . '" alt="signature_customer" style="width: 100px; height: 100px;">';
        })
        ->editColumn('signature_couple', function ($prospectiveCustomerSurvey) {
            return '<img src="' . asset('storage/' . $prospectiveCustomerSurvey->signature_couple) . '" alt="signature_couple" style="width: 100px; height: 100px;">';
        })
        ->addColumn('workplace_image', function ($prospectiveCustomerSurvey) {
            // Passing 2 image workplace_image1 and workplace_image2 if exist
            $workplace_image1 = $prospectiveCustomerSurvey->workplace_image1 ?? '-';
            $workplace_image2 = $prospectiveCustomerSurvey->workplace_image2 ?? '-';
            return '<img src="' . asset('storage/' . $workplace_image1) . '" alt="workplace_image1" style="width: 100px; height: 100px;"><img src="' . asset('storage/'. $workplace_image2) . '" alt="workplace_image2" style="width: 100px; height: 100px;">';
        })
        ->addColumn('customer_and_ktp_image', function ($prospectiveCustomerSurvey) {
            // Passing 2 image customer_and_ktp_image1 and customer_and_ktp_image2 if exist
            $customer_image = $prospectiveCustomerSurvey->customer_image ?? '-';
            $ktp_image = $prospectiveCustomerSurvey->ktp_image ?? '-';
            return '<img src="' . asset('storage/' . $customer_image) . '" alt="customer_and_ktp_image1" style="width: 100px; height: 100px;"><img src="' . asset('storage/'. $ktp_image) . '" alt="customer_and_ktp_image2" style="width: 100px; height: 100px;">';
        })
        ->addColumn('loan_guarantee_image', function ($prospectiveCustomerSurvey) {
            // Passing 2 image loan_guarantee_image1 and loan_guarantee_image2 if exist
            $loan_guarantee_image1 = $prospectiveCustomerSurvey->loan_guarantee_image1 ?? '-';
            $loan_guarantee_image2 = $prospectiveCustomerSurvey->loan_guarantee_image2 ?? '-';
            return '<img src="' . asset('storage/' . $loan_guarantee_image1) . '" alt="loan_guarantee_image1" style="width: 100px; height: 100px;"><img src="' . asset('storage/'. $loan_guarantee_image2) . '" alt="loan_guarantee_image2" style="width: 100px; height: 100px;">';
        })
        ->addColumn('kk_and_id_card_image', function ($prospectiveCustomerSurvey) {
            // Passing 2 image kk_and_id_card_image1 and kk_and_id_card_image2 if exist
            $kk_image = $prospectiveCustomerSurvey->kk_image ?? '-';
            $id_card_image = $prospectiveCustomerSurvey->id_card_image ?? '-';
            return '<img src="' . asset('storage/' . $kk_image) . '" alt="kk_and_id_card_image1" style="width: 100px; height: 100px;"><img src="' . asset('storage/'. $id_card_image) . '" alt="kk_and_id_card_image2" style="width: 100px; height: 100px;">';
        })
        ->addColumn('salary_slip_image', function ($prospectiveCustomerSurvey) {
            // Passing 2 image salary_slip_image1 and salary_slip_image2 if exist
            $salary_slip_image1 = $prospectiveCustomerSurvey->salary_slip_image1 ?? '-';
            $salary_slip_image2 = $prospectiveCustomerSurvey->salary_slip_image2 ?? '-';
            return '<img src="' . asset('storage/' . $salary_slip_image1) . '" alt="salary_slip_image1" style="width: 100px; height: 100px;"><img src="' . asset('storage/'. $salary_slip_image2) . '" alt="salary_slip_image2" style="width: 100px; height: 100px;">';
        })
        ->addColumn('action', function ($prospectiveCustomerSurvey) {
            return view('prospective-customer-surveys.action', ['value' => $prospectiveCustomerSurvey]);
        })
        ->addColumn('details', function ($prospectiveCustomerSurvey) {
            return;
        })
        ->rawColumns(['select', 'status', 'signature_officer', 'signature_customer', 'signature_couple', 'workplace_image', 'customer_and_ktp_image', 'loan_guarantee_image', 'kk_and_id_card_image', 'salary_slip_image', 'action'])
        ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();

        return view('prospective-customer-surveys.create', [
            'users' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data
        $validatedData = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'status' => 'nullable|in:pending,ongoing,done',
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'number_ktp' => 'required|string|unique:prospective_customer_surveys,number_ktp',
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

        // Daftar field gambar yang akan di-upload
        $imageFields = [
            'signature_officer', 'signature_customer', 'signature_couple',
            'workplace_image1', 'workplace_image2', 'customer_image',
            'ktp_image', 'loan_guarantee_image1', 'loan_guarantee_image2',
            'kk_image', 'id_card_image', 'salary_slip_image1', 'salary_slip_image2'
        ];

        // Menyimpan path file yang di-upload untuk rollback jika terjadi error
        $uploadedImages = [];

        DB::beginTransaction(); // Mulai transaksi

        try {
            // Upload gambar jika ada
            foreach ($imageFields as $field) {
                if ($request->hasFile($field)) {
                    $path = $request->file($field)->store('survey', 'public');
                    $validatedData[$field] = $path;
                    $uploadedImages[] = $path; // Simpan path untuk rollback jika ada error
                }
            }

            // Simpan data ke database
            $survey = ProspectiveCustomerSurvey::create($validatedData);

            DB::commit(); // Commit transaksi jika semua berhasil

            return redirect()->route('prospective-customer-surveys.index')->with('success', 'Survey created successfully');
        } catch (ValidationException $e) {
            DB::rollBack(); // Rollback jika validasi gagal

            // Hapus file yang sudah terunggah
            foreach ($uploadedImages as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            return redirect()->back()->with('error', 'Failed to create survey: ' . $e->getMessage());
        } catch (Exception $e) {
            DB::rollBack(); // Rollback jika terjadi error lainnya

            // Hapus file yang sudah terunggah
            foreach ($uploadedImages as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            return redirect()->back()->with('error', 'Failed to create survey: ' . $e->getMessage());
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
        $prospectiveCustomerSurvey = ProspectiveCustomerSurvey::findOrFail($id);
        $users = User::all();

        return view('prospective-customer-surveys.edit', [
            'data' => $prospectiveCustomerSurvey,
            'users' => $users
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $survey = ProspectiveCustomerSurvey::findOrFail($id);

        $validatedData = $request->validate([
            // 'status' => 'nullable|in:pending,ongoing,done',
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
            // Upload gambar jika ada
            foreach ($imageFields as $field) {
                if ($request->hasFile($field)) {
                    // Simpan file lama untuk dihapus jika upload sukses
                    if ($survey->$field) {
                        $oldImages[$field] = $survey->$field;
                    }

                    // Simpan file baru
                    $path = $request->file($field)->store('survey', 'public');
                    $validatedData[$field] = $path;
                    $uploadedImages[$field] = $path;
                }
            }

            // Update data di database
            $survey->update($validatedData);

            // Hapus file lama jika update berhasil
            foreach ($oldImages as $oldPath) {
                Storage::disk('public')->delete($oldPath);
            }

            DB::commit();
            return redirect()->route('prospective-customer-surveys.index')->with('success', 'Survey updated successfully');
        } catch (Exception $e) {
            DB::rollBack();

            // Hapus file baru yang diunggah jika terjadi error
            foreach ($uploadedImages as $newPath) {
                Storage::disk('public')->delete($newPath);
            }

            return redirect()->back()->with('error', 'Failed to update survey: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $prospectiveCustomerSurvey = ProspectiveCustomerSurvey::findOrFail($id);
        // $prospectiveCustomerSurvey->deleted_by = $user->id;
        $prospectiveCustomerSurvey->save();
        $prospectiveCustomerSurvey->delete();

        return redirect()->route('prospective-customer-surveys.index')->with('success', 'Data berhasil dihapus');
    }

    public function massDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|exists:prospective_customer_surveys,id',
        ]);

        $ids = $request->input('ids', []);
        $user = auth()->user();

        foreach ($ids as $id) {
            $survey = ProspectiveCustomerSurvey::findOrFail($id);
            // $survey->deleted_by = $user->id;
            $survey->save();
            $survey->delete();
        }

        return redirect()->route('prospective-customer-surveys.index')->with('success', 'Data berhasil dihapus');
    }

    public function massSelectOfficer(Request $request)
    {
        $validatedData = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|exists:prospective_customer_surveys,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $ids = $request->input('ids', []);
        $user = auth()->user();

        foreach ($ids as $id) {
            $survey = ProspectiveCustomerSurvey::findOrFail($id);
            $survey->user_id = $validatedData['user_id'];
            // $survey->updated_by = $user->id;
            $survey->save();
        }

        return redirect()->route('prospective-customer-surveys.index')->with('success', 'Data berhasil ditandatangani');
    }

    public function exportPdfByCustomer(string $id)
    {
        $survey = ProspectiveCustomerSurvey::findOrFail($id);

        $pdf = Pdf::loadView('prospective-customer-surveys.pdf-by-customer', [
            'data' => $survey,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('pdf');
    }
}
