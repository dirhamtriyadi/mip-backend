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
            ->latest()
            ->first();

        if (!$leaves) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found.',
                'errors' => [
                    'id' => 'Data not found.',
                ],
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully.',
            'data' => new LeaveResource($leaves),
        ], 200);
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
                'message' => 'Validation Error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validatedData = $validator->validated();

        $validatedData['user_id'] = auth()->user()->id;

        $leave = Leave::create($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Cuti berhasil diajukan',
            'data' => new LeaveResource($leave),
        ], 200);
    }
}
