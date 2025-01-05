<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leave;
use App\Http\Resources\Api\V1\LeaveResource;
use Validator;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $leaves = Leave::with('user.detail_users')
            ->where('user_id', auth()->user()->id)
            ->first();

        if (!$leaves) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found.'
            ], 200);
        }

        return response()->json([
            'status' => 'success',
            'data' => new LeaveResource($leaves)
        ]);
    }

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
