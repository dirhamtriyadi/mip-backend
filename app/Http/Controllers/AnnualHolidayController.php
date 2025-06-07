<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use App\Models\AnnualHoliday;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Helpers\LoggerHelper;
use Illuminate\Validation\ValidationException;

class AnnualHolidayController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            // 'auth',
            // new Middleware('subscribed', except: ['store']),
            new Middleware('permission:hari-libur.index', only: ['index']),
            new Middleware('permission:hari-libur.create', only: ['index', 'create', 'store']),
            new Middleware('permission:hari-libur.edit', only: ['index', 'edit', 'update']),
            new Middleware('permission:hari-libur.delete', only: ['index', 'destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('annual-holidays.index');
    }

    // Fetch data for DataTable
    public function fetchDataTable(Request $request)
    {
        // load all annual holidays
        $annualHolidays = AnnualHoliday::latest()->get();

        return DataTables::of($annualHolidays)
            ->addIndexColumn()
            ->editColumn('holiday_date', function ($annualHoliday) {
                return Carbon::parse($annualHoliday->holiday_date)->format('d-m-Y');
            })
            ->addColumn('action', function ($annualHoliday) {
                return view('annual-holidays.action', ['value' => $annualHoliday]);
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('annual-holidays.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            //code...
            $validatedData = $request->validate([
                'holiday_date' => 'required|date',
                'description' => 'required|string'
            ]);

            AnnualHoliday::create($validatedData);

            return redirect()->route('annual-holidays.index')->with('success', 'Data berhasil disimpan');
        } catch (ValidationException $e) {
            LoggerHelper::logError($e);

            // Jika ada error validasi, kembalikan dengan pesan error
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Throwable $th) {
            //throw $th;
            LoggerHelper::logError($th);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $th->getMessage());
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
        $annualHoliday = AnnualHoliday::findOrFail($id);

        return view('annual-holidays.edit', [
            'data' => $annualHoliday
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
                'holiday_date' => 'required|date',
                'description' => 'required|string'
            ]);

            $annualHoliday = AnnualHoliday::findOrFail($id);
            $annualHoliday->update($validatedData);

            return redirect()->route('annual-holidays.index')->with('success', 'Data berhasil diubah');
        } catch (ValidationException $e) {
            LoggerHelper::logError($e);

            // Jika ada error validasi, kembalikan dengan pesan error
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Throwable $th) {
            //throw $th;
            LoggerHelper::logError($th);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengubah data: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            //code...
            $annualHoliday = AnnualHoliday::findOrFail($id);
            $annualHoliday->delete();

            return redirect()->route('annual-holidays.index')->with('success', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            //throw $th;
            LoggerHelper::logError($th);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data: ' . $th->getMessage());
        }

    }
}
