<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\ProfileResource;
use App\Http\Resources\Api\V1\UserResource;
use Validator;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user()->load('detail_users');

        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully.',
            'data' => new ProfileResource($user),
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = auth()->user()->id;
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found.',
                'errors' => [
                    'id' => 'Data not found.',
                ],
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'email|unique:users,email,'.$id,
            // validate input nik if exists in detail_users table nik update nik if not exists create new detail_users
            // 'nik' => 'required|unique:detail_users,nik,' . ($user->detail_users ? $user->detail_users->id : null),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation Error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Update user data with relation to detail_users->nik if exists nik from detail_users update nik if not exists create new detail_users
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        // $user->detail_users()->updateOrCreate(['user_id' => $id], ['nik' => $request->nik]);

        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully.',
            'data' => new UserResource($user),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function updatePassword(Request $request)
    {
        $id = auth()->user()->id;
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found.',
                'errors' => [
                    'id' => 'Data not found.',
                ],
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'password' => 'required|same:confirm_password'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation Error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Update password
        $user->update([
            'password' => bcrypt($request->password)
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User updated password successfully.',
            'data' => new UserResource($user),
        ], 200);
    }
}
