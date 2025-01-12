<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use App\Models\AnnualHoliday;
use App\Models\WorkSchedule;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class AttendanceController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            // 'auth',
            // new Middleware('subscribed', except: ['store']),
            new Middleware('permission:absen.index', only: ['index']),
            new Middleware('permission:absen.create', only: ['index', 'create', 'store']),
            new Middleware('permission:absen.edit', only: ['index', 'edit', 'update']),
            new Middleware('permission:absen.delete', only: ['index', 'destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('attendances.index');
    }

    // Fetch data for DataTable
    public function fetchDataTable(Request $request)
    {
        // load all attendances with their user
        $attendaces = Attendance::with('user')
            ->latest()
            ->orderBy('date', 'desc')
            ->get();

        return DataTables::of($attendaces)
            ->addIndexColumn()
            ->addColumn('user', function ($attendance) {
                return $attendance->user->name;
            })
            ->editColumn('date', function ($attendance) {
                return Carbon::parse($attendance->date)->format('d-m-Y');
            })
            ->editColumn('time_check_in', function ($attendance) {
                return Carbon::parse($attendance->time_check_in)->format('H:i');
            })
            ->editColumn('time_check_out', function ($attendance) {
                return $attendance->time_check_out ? Carbon::parse($attendance->time_check_out)->format('H:i') : '-';
            })
            ->editColumn('type', function ($attendance) {
                return view('attendances.type', ['value' => $attendance]);
            })
            ->addColumn('image', function ($attendance) {
                return view('attendances.image', ['value' => $attendance]);
            })
            ->addColumn('location', function ($attendance) {
                return view('attendances.location', ['value' => $attendance]);
            })
            ->addColumn('action', function ($attendance) {
                return view('attendances.action', ['value' => $attendance]);
            })
            ->rawColumns(['type', 'action'])
            ->toJson();
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
