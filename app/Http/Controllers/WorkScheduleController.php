<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkSchedule;

class WorkScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workSchedules = WorkSchedule::all();

        return view('work-schedules.index', [
            'data' => $workSchedules
        ]);
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $workSchedule = WorkSchedule::findOrFail($id);
        $workSchedule->delete();

        return redirect()->route('work-schedules.index')->with('success', 'Data berhasil dihapus');
    }
}
