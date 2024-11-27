<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnnualHoliday;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class AnnualHolidayController extends Controller
{
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
        $validatedData = $request->validate([
            'holiday_date' => 'required|date',
            'description' => 'required|string'
        ]);

        AnnualHoliday::create($validatedData);

        return redirect()->route('annual-holidays.index')->with('success', 'Data berhasil disimpan');
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
        $validatedData = $request->validate([
            'holiday_date' => 'required|date',
            'description' => 'required|string'
        ]);

        $annualHoliday = AnnualHoliday::findOrFail($id);
        $annualHoliday->update($validatedData);

        return redirect()->route('annual-holidays.index')->with('success', 'Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $annualHoliday = AnnualHoliday::findOrFail($id);
        $annualHoliday->delete();

        return redirect()->route('annual-holidays.index')->with('success', 'Data berhasil dihapus');

    }
}
