<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use App\Models\WorkSchedule;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Helpers\LoggerHelper;
use Illuminate\Validation\ValidationException;

class WorkScheduleController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            // 'auth',
            // new Middleware('subscribed', except: ['store']),
            new Middleware('permission:jam-kerja.index', only: ['index']),
            new Middleware('permission:jam-kerja.create', only: ['index', 'create', 'store']),
            new Middleware('permission:jam-kerja.edit', only: ['index', 'edit', 'update']),
            new Middleware('permission:jam-kerja.delete', only: ['index', 'destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('work-schedules.index');
    }

    // Fetch data for DataTable
    public function fetchDataTable(Request $request)
    {
        // load all work schedules
        $workSchedules = WorkSchedule::all();

        return DataTables::of($workSchedules)
            ->addIndexColumn()
            ->editColumn('work_start_time', function ($workSchedule) {
                return Carbon::parse($workSchedule->work_start_time)->format('H:i');
            })
            ->editColumn('work_end_time', function ($workSchedule) {
                return Carbon::parse($workSchedule->work_end_time)->format('H:i');
            })
            ->addColumn('action', function ($workSchedule) {
                return view('work-schedules.action', ['value' => $workSchedule]);
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('work-schedules.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            //code...
            $validatedData = $request->validate([
                'work_start_time' => 'required|date_format:H:i',
                'work_end_time' => 'required|date_format:H:i',
                'working_days_json' => 'required|json',
            ]);

            $tempData = [
                'work_start_time' => $validatedData['work_start_time'],
                'work_end_time' => $validatedData['work_end_time'],
                'working_days' => $validatedData['working_days_json'],
            ];

            WorkSchedule::create($tempData);

            return redirect()->route('work-schedules.index')->with('success', 'Data berhasil ditambahkan');
        } catch (ValidationException $e) {
            LoggerHelper::logError($e);

            // Jika ada error validasi, kembalikan dengan pesan error
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Throwable $th) {
            //throw $th;
            LoggerHelper::logError($th);

            return redirect()->back()->withErrors(['general' => 'Gagal menyimpan data: ' . $th->getMessage()])->withInput();
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
        $workSchedule = WorkSchedule::findOrFail($id);

        return view('work-schedules.edit', [
            'data' => $workSchedule
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
                'work_start_time' => 'required|date_format:H:i',
                'work_end_time' => 'required|date_format:H:i',
                'working_days_json' => 'required|json',
            ]);

            $workSchedule = WorkSchedule::findOrFail($id);
            $workSchedule->work_start_time = $validatedData['work_start_time'];
            $workSchedule->work_end_time = $validatedData['work_end_time'];
            $workSchedule->working_days = $validatedData['working_days_json'];
            $workSchedule->save();

            return redirect()->route('work-schedules.index')->with('success', 'Work schedule updated successfully.');
        } catch (ValidationException $e) {
            LoggerHelper::logError($e);

            // Jika ada error validasi, kembalikan dengan pesan error
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Throwable $th) {
            //throw $th;
            LoggerHelper::logError($th);

            return redirect()->back()->withErrors(['general' => 'Gagal memperbarui data: ' . $th->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            //code...
            $workSchedule = WorkSchedule::findOrFail($id);
            $workSchedule->delete();

            return redirect()->route('work-schedules.index')->with('success', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            //throw $th;
            LoggerHelper::logError($th);

            return redirect()->back()->withErrors(['general' => 'Gagal menghapus data: ' . $th->getMessage()])->withInput();
        }
    }
}
