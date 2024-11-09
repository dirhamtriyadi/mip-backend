<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use App\Models\AnnualHoliday;
use App\Models\WorkSchedule;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendaces = Attendance::latest()->get();

        return view('attendances.index', [
            'data' => $attendaces
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();

        return view('attendances.create', [
            'users' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'code' => 'required',
            'date' => 'required|date',
            'time_check_in' => 'required|date_format:H:i',
            'time_check_out' => 'nullable|date_format:H:i',
            'type' => 'required|in:present,sick,permit',
            'reason_late' => 'nullable',
            'reason_early_out' => 'nullable',
            'image_check_in' => 'nullable|image',
            'image_check_out' => 'nullable|image',
            'location_check_in' => 'nullable',
            'location_check_out' => 'nullable',
        ]);

        // Cek apakah hari ini adalah hari libur
        $isHoliday = AnnualHoliday::where('holiday_date', $validatedData['date'])->exists();
        if ($isHoliday) {
            return redirect()->back()->with('error', 'Tanggal yang dipilih adalah hari libur');
        }

        // Ambil jadwal kerja
        $workSchedule = WorkSchedule::first();
        if (!$workSchedule) {
            return redirect()->back()->with('error', 'Jadwal kerja belum diatur');
        }

        // Periksa apakah hari ini adalah hari kerja
        $dayOfWeek = Carbon::parse($validatedData['date'])->format('l');
        if (!in_array($dayOfWeek, $workSchedule->working_days)) {
            dd('Hari ini bukan hari kerja', $dayOfWeek, $workSchedule->working_days);
            return redirect()->back()->with('error', 'Hari ini bukan hari kerja');
        }

        // Hitung keterlambatan
        $checkInTime = Carbon::parse($validatedData['time_check_in']);
        $workStartTime = Carbon::parse($workSchedule->work_start_time);
        $lateDuration = $checkInTime->greaterThan($workStartTime) ? $checkInTime->diffInMinutes($workStartTime) : 0;
        $validatedData['late_duration'] = $lateDuration;

        // Hitung pulang lebih awal
        if ($validatedData['time_check_out']) {
            $checkOutTime = Carbon::parse($validatedData['time_check_out']);
            $workEndTime = Carbon::parse($workSchedule->work_end_time);
            $earlyLeaveDuration = $checkOutTime->lessThan($workEndTime) ? $workEndTime->diffInMinutes($checkOutTime) : 0;
            $validatedData['early_leave_duration'] = $earlyLeaveDuration;
        }

        // save image check in
        if ($request->hasFile('image_check_in')) {
            // save image to public/images/attendances and change name file to name user-timestamp
            $file = $request->file('image_check_in');
            $fileName = $validatedData['user_id'] . '-' . 'masuk' . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/attendances'), $fileName);
            $validatedData['image_check_in'] = $fileName;
        }

        // save image check out
        if ($request->hasFile('image_check_out')) {
            // save image to public/images/attendances and change name file to name user-timestamp
            $file = $request->file('image_check_out');
            $fileName = $validatedData['user_id'] . '-' . 'pulang' . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/attendances'), $fileName);
            $validatedData['image_check_out'] = $fileName;
        }

        $validatedData['created_by'] = auth()->id();
        // $validatedData['updated_by'] = auth()->id();
        // $validatedData['deleted_by'] = auth()->id();

        Attendance::create($validatedData);

        return redirect()->route('attendances.index')->with('success', 'Data berhasil ditambahkan');
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
        $attendance = Attendance::findOrFail($id);
        $users = User::all();

        return view('attendances.edit', [
            'data' => $attendance,
            'users' => $users
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'code' => 'required',
            'date' => 'required|date',
            'time_check_in' => 'required|date_format:H:i',
            'time_check_out' => 'nullable|date_format:H:i',
            'type' => 'required|in:present,sick,permit',
            'reason_late' => 'nullable',
            'reason_early_out' => 'nullable',
            'image_check_in' => 'nullable|image',
            'image_check_out' => 'nullable|image',
            'location_check_in' => 'nullable',
            'location_check_out' => 'nullable',
        ]);

        // Ambil jadwal kerja
        $isHoliday = AnnualHoliday::where('holiday_date', $validatedData['date'])->exists();
        if ($isHoliday) {
            return redirect()->back()->with('error', 'Tanggal yang dipilih adalah hari libur');
        }

        // Ambil jadwal kerja
        $workSchedule = WorkSchedule::first();
        if (!$workSchedule) {
            return redirect()->back()->with('error', 'Jadwal kerja belum diatur');
        }

        // Periksa apakah hari ini adalah hari kerja
        $dayOfWeek = Carbon::parse($validatedData['date'])->format('l');
        if (!in_array($dayOfWeek, $workSchedule->working_days)) {
            return redirect()->back()->with('error', 'Tanggal yang dipilih adalah hari libur');
        }

        // Hitung keterlambatan
        $checkInTime = Carbon::parse($validatedData['time_check_in']);
        $workStartTime = Carbon::parse($workSchedule->work_start_time);
        $lateDuration = $checkInTime->greaterThan($workStartTime) ? $checkInTime->diffInMinutes($workStartTime) : 0;
        $validatedData['late_duration'] = $lateDuration;

        // Hitung pulang lebih awal
        if ($validatedData['time_check_out']) {
            $checkOutTime = Carbon::parse($validatedData['time_check_out']);
            $workEndTime = Carbon::parse($workSchedule->work_end_time);
            $earlyLeaveDuration = $checkOutTime->lessThan($workEndTime) ? $workEndTime->diffInMinutes($checkOutTime) : 0;
            $validatedData['early_leave_duration'] = $earlyLeaveDuration;
        }

        $attendance = Attendance::findOrFail($id);

        // save image check in
        if ($request->hasFile('image_check_in')) {
            // remove old image
            if (file_exists(public_path('images/attendances/' . $attendance->image_check_in))) {
                unlink(public_path('images/attendances/' . $attendance->image_check_in));
            }

            // save image to public/images/attendances and change name file to name user-timestamp
            $file = $request->file('image_check_in');
            $fileName = $validatedData['user_id'] . '-' . 'masuk' . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/attendances'), $fileName);
            $validatedData['image_check_in'] = $fileName;
        }

        // save image check out
        if ($request->hasFile('image_check_out')) {
            // remove old image
            if (file_exists(public_path('images/attendances/' . $attendance->image_check_out))) {
                unlink(public_path('images/attendances/' . $attendance->image_check_out));
            }

            // save image to public/images/attendances and change name file to name user-timestamp
            $file = $request->file('image_check_out');
            $fileName = $validatedData['user_id'] . '-' . 'pulang' . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/attendances'), $fileName);
            $validatedData['image_check_out'] = $fileName;
        }

        $validatedData['updated_by'] = auth()->id();

        $attendance->update($validatedData);

        return redirect()->route('attendances.index')->with('success', 'Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $attendance = Attendance::findOrFail($id);

        // remove image
        // if (file_exists(public_path('images/attendances/' . $attendance->image))) {
        //     unlink(public_path('images/attendances/' . $attendance->image));
        // }

        $attendance->deleted_by = auth()->id();
        $attendance->save();
        $attendance->delete();

        return redirect()->route('attendances.index')->with('success', 'Data berhasil dihapus');
    }
}
