<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\AnnualHoliday;
use App\Models\WorkSchedule;
use App\Models\Attendance;
use Carbon\Carbon;
use App\Http\Resources\Api\V1\AttendanceResource;

class AttendanceController extends Controller
{
    public function workSchedules()
    {
        $workSchedule = WorkSchedule::first();

        return response()->json([
            'status' => 'success',
            'message' => 'Data jadwal kerja berhasil diambil.',
            'data' => $workSchedule,
        ], 200);
    }

    public function checkIn(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            // 'code' => 'required',
            'date' => 'required|date',
            'time_check_in' => 'required|date_format:H:i:s',
            'type' => 'required|in:present,sick,permit',
            'reason_late' => 'nullable',
            'image_check_in' => 'nullable|image',
            'location_check_in' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 400);
        }

        $validatedData = $validator->validated();

        // Cek apakah hari ini adalah hari libur
        $isHoliday = AnnualHoliday::where('holiday_date', $validatedData['date'])->exists();
        if ($isHoliday) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hari ini adalah hari libur.',
            ], 400);
        }

        // Ambil jadwal kerja
        $workSchedule = WorkSchedule::first();
        if (!$workSchedule) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jadwal kerja belum diatur.',
            ], 400);
        }

        // Periksa apakah hari ini adalah hari kerja
        $dayOfWeek = Carbon::parse($validatedData['date'])->format('l');
        if (!in_array($dayOfWeek, $workSchedule->working_days)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hari ini bukan hari kerja.',
            ], 400);
        }

        // Cek apakah user sudah absen masuk hari ini
        $isCheckIn = Attendance::where('user_id', $user->id)
            ->where('date', $validatedData['date'])
            ->where('type', 'present')
            ->exists();
        if ($isCheckIn) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah absen masuk hari ini.',
            ], 400);
        }

        // Cek apakah user absen sakit hari ini
        $isSickIn = Attendance::where('user_id', $user->id)
            ->where('date', $validatedData['date'])
            ->where('type', 'sick')
            ->exists();
        if ($isSickIn) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah absen sakit hari ini.'
            ], 400);
        }

        // Cek apakah user absen izin hari ini
        $isPermitIn = Attendance::where('user_id', $user->id)
            ->where('date', $validatedData['date'])
            ->where('type', 'permit')
            ->exists();
        if ($isPermitIn) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah absen izin hari ini.'
            ], 400);
        }

        // Hitung keterlambatan
        $checkInTime = Carbon::parse($validatedData['time_check_in']);
        $workStartTime = Carbon::parse($workSchedule->work_start_time);
        $lateDuration = $checkInTime->greaterThan($workStartTime) ? $checkInTime->diffInMinutes($workStartTime) : 0;
        $validatedData['late_duration'] = $lateDuration;

        // Save image check in
        if ($request->hasFile('image_check_in')) {
            // save image to public/images/attendances and change name file to name user-timestamp
            $file = $request->file('image_check_in');
            $fileName = $user->id . '-' . 'masuk' . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/attendances'), $fileName);
            $validatedData['image_check_in'] = $fileName;
        }

        // generate code absen masuk (nama user + 1/1/2021)
        $validatedData['code'] = $user->name . Carbon::parse($validatedData['date'])->format('d/m/Y');
        $validatedData['user_id'] = auth()->id();
        $validatedData['created_by'] = auth()->id();

        $data = Attendance::create($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Absen masuk berhasil.',
            'data' => new AttendanceResource($data),
        ], 201);
    }

    public function checkOut(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'time_check_out' => 'required|date_format:H:i:s',
            'reason_early_out' => 'nullable',
            'image_check_out' => 'nullable|image',
            'location_check_out' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 400);
        }

        $validatedData = $validator->validated();
        $user = auth()->user();

        // Cek apakah user sudah absen masuk hari ini
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $validatedData['date'])
            ->where('type', 'present')
            ->first();
        if (!$attendance) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda belum absen masuk hari ini.',
            ], 400);
        }

        // Cek apakah user sudah absen pulang hari ini
        if ($attendance->time_check_out) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah absen pulang hari ini.',
            ], 400);
        }

        // Ambil jadwal kerja
        $workSchedule = WorkSchedule::first();
        if (!$workSchedule) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jadwal kerja belum diatur.',
            ], 400);
        }

        // Hitung pulang lebih awal
        $checkOutTime = Carbon::parse($validatedData['time_check_out']);
        $workEndTime = Carbon::parse($workSchedule->work_end_time);
        $earlyLeaveDuration = $checkOutTime->lessThan($workEndTime) ? $workEndTime->diffInMinutes($checkOutTime) : 0;
        $validatedData['early_leave_duration'] = $earlyLeaveDuration;

        // Save image check out
        if ($request->hasFile('image_check_out')) {
            // save image to public/images/attendances and change name file to name user-timestamp
            $file = $request->file('image_check_out');
            $fileName = $user->id . '-' . 'pulang' . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/attendances'), $fileName);
            $validatedData['image_check_out'] = $fileName;
        }

        $validatedData['updated_by'] = auth()->id();

        $attendance->update($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Absen pulang berhasil.',
            'data' => new AttendanceResource($attendance),
        ], 200);
    }

    public function sick(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'time_check_in' => 'required|date_format:H:i:s',
            'type' => 'required|in:present,sick,permit',
            'image_check_in' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'location_check_in' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 400);
        }

        $validatedData = $validator->validated();

        // Cek apakah hari ini adalah hari libur
        $isHoliday = AnnualHoliday::where('holiday_date', $validatedData['start_date'])->exists();
        if ($isHoliday) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hari ini adalah hari libur.',
            ], 400);
        }

        // Ambil jadwal kerja
        $workSchedule = WorkSchedule::first();
        if (!$workSchedule) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jadwal kerja belum diatur.',
            ], 400);
        }

        // Periksa apakah hari ini adalah hari kerja
        $dayOfWeek = Carbon::parse($validatedData['start_date'])->format('l');
        if (!in_array($dayOfWeek, $workSchedule->working_days)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hari ini bukan hari kerja.',
            ], 400);
        }

        // Cek apakah user sudah absen masuk hari ini
        $isCheckIn = Attendance::where('user_id', $user->id)
            ->where('date', $validatedData['start_date'])
            ->where('type', 'present')
            ->exists();
        if ($isCheckIn) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah absen masuk hari ini.',
            ], 400);
        }

        // Cek apakah user absen sakit hari ini
        $isSickIn = Attendance::where('user_id', $user->id)
            ->where('date', $validatedData['start_date'])
            ->where('type', 'sick')
            ->exists();
        if ($isSickIn) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah absen sakit hari ini.'
            ], 400);
        }

        // Cek apakah user absen izin hari ini
        $isPermitIn = Attendance::where('user_id', $user->id)
            ->where('date', $validatedData['start_date'])
            ->where('type', 'permit')
            ->exists();
        if ($isPermitIn) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah absen izin hari ini.'
            ], 400);
        }

        // Save image check in
        if ($request->hasFile('image_check_in')) {
            // save image to public/images/attendances and change name file to name user-timestamp
            $file = $request->file('image_check_in');
            $fileName = $user->id . '-' . 'masuk' . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/attendances'), $fileName);
            $validatedData['image_check_in'] = $fileName;
        }

        // mapping $start_date to $end_date for input data
        $datas = [];
        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        for ($date = $start; $date->lte($end); $date->addDay()) {
            // generate code absen masuk (nama user + 1/1/2021)
            $validatedData['code'] = $user->name . $date->format('Y-m-d');
            $validatedData['user_id'] = $user->id;
            $validatedData['created_by'] = $user->id;

            $data = Attendance::create([
                'user_id' => $validatedData['user_id'],
                'code' => $validatedData['code'],
                'date' => $date->format('Y-m-d'),
                'time_check_in' => $validatedData['time_check_in'],
                'type' => $validatedData['type'],
                'image_check_in' => $validatedData['image_check_in'],
                'location_check_in' => $validatedData['location_check_in'],
            ]);

            $datas[] = $data;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Absen sakit berhasil.',
            'data' => new AttendanceResource($datas),
        ], 201);
    }

    public function permit(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            // 'code' => 'required',
            'date' => 'required|date',
            'time_check_in' => 'required|date_format:H:i:s',
            'type' => 'required|in:present,sick,permit',
            'reason_late' => 'nullable',
            'image_check_in' => 'nullable|image',
            'location_check_in' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 400);
        }

        $validatedData = $validator->validated();

        // Cek apakah hari ini adalah hari libur
        $isHoliday = AnnualHoliday::where('holiday_date', $validatedData['date'])->exists();
        if ($isHoliday) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hari ini adalah hari libur.',
            ], 400);
        }

        // Ambil jadwal kerja
        $workSchedule = WorkSchedule::first();
        if (!$workSchedule) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jadwal kerja belum diatur.',
            ], 400);
        }

        // Periksa apakah hari ini adalah hari kerja
        $dayOfWeek = Carbon::parse($validatedData['date'])->format('l');
        if (!in_array($dayOfWeek, $workSchedule->working_days)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hari ini bukan hari kerja.',
            ], 400);
        }

        // Cek apakah user sudah absen masuk hari ini
        $isCheckIn = Attendance::where('user_id', $user->id)
            ->where('date', $validatedData['date'])
            ->where('type', 'present')
            ->exists();
        if ($isCheckIn) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah absen masuk hari ini.',
            ], 400);
        }

        // Cek apakah user absen sakit hari ini
        $isSickIn = Attendance::where('user_id', $user->id)
            ->where('date', $validatedData['date'])
            ->where('type', 'sick')
            ->exists();
        if ($isSickIn) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah absen sakit hari ini.'
            ], 400);
        }

        // Cek apakah user absen izin hari ini
        $isPermitIn = Attendance::where('user_id', $user->id)
            ->where('date', $validatedData['date'])
            ->where('type', 'permit')
            ->exists();
        if ($isPermitIn) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah absen izin hari ini.'
            ], 400);
        }

        // Save image check in
        if ($request->hasFile('image_check_in')) {
            // save image to public/images/attendances and change name file to name user-timestamp
            $file = $request->file('image_check_in');
            $fileName = $user->id . '-' . 'masuk' . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/attendances'), $fileName);
            $validatedData['image_check_in'] = $fileName;
        }

        // generate code absen masuk (nama user + 1/1/2021)
        $validatedData['code'] = $user->name . Carbon::parse($validatedData['date'])->format('d/m/Y');
        $validatedData['user_id'] = auth()->id();
        $validatedData['created_by'] = auth()->id();

        $data = Attendance::create($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Absen izin berhasil.',
            'data' => new AttendanceResource($data),
        ], 201);
    }
}
