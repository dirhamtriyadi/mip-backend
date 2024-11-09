<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leave;
use Validator;

class LeaveController extends Controller
{
    public function submission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $validatedData = $validator->validated();

        $validatedData['user_id'] = auth()->user()->id;

        $leave = Leave::create($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Cuti berhasil diajukan',
            'data' => $leave
        ]);
    }
}
