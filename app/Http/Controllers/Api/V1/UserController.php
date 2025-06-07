<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Http\Resources\Api\V1\UserResource;
use App\Helpers\LoggerHelper;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            //code...
            $data = User::with('roles')->latest()->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Data retrieved successfully.',
                'data' => UserResource::collection($data),
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            LoggerHelper::logError($th);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve data.',
                'errors' => [
                    'general' => [$th->getMessage()], // atau
                    'exception' => $th->getMessage()
                ]
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            //code...
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'email|unique:users,email',
                'password' => 'required|same:password_confirmation'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation Error.',
                    'errors' => $validator->errors(),
                ], 400);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully.',
                'data' => new UserResource($user),
            ], 201);
        } catch (\Throwable $th) {
            //throw $th;
            LoggerHelper::logError($th);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create user.',
                'errors' => [
                    'general' => [$th->getMessage()], // atau
                    'exception' => $th->getMessage()
                ]
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            //code...
            $user = User::with('roles.permissions')->find($id);

            if (is_null($user)) {
                return response()->json('Data not found.', 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data retrieved successfully.',
                'data' => new UserResource($user)
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            LoggerHelper::logError($th);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve data.',
                'errors' => [
                    'general' => [$th->getMessage()], // atau
                    'exception' => $th->getMessage()
                ]
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        try {
            //code...
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'email|unique:users,email,'.$id,
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation Error.',
                    'errors' => $validator->errors(),
                ], 400);
            }

            $user = User::find($id);
            if ($request->password) {
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => bcrypt($request->password)
                ]);
            }
            $user->update([
                'name' => $request->name,
                'email' => $request->email
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully.',
                'data' => new UserResource($user),
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            LoggerHelper::logError($th);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update user.',
                'errors' => [
                    'general' => [$th->getMessage()], // atau
                    'exception' => $th->getMessage()
                ]
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            //code...
            $user = User::find($id);
            $user->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully.',
                'data' => new UserResource($user),
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            LoggerHelper::logError($th);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete user.',
                'errors' => [
                    'general' => [$th->getMessage()], // atau
                    'exception' => $th->getMessage()
                ]
            ], 500);
        }
    }
}
