<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Models\User;
use App\Http\Resources\Api\V1\UserResource;

class AuthController extends Controller
{
    public function me(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated',
                ], 401);
            }

            // Load relationships jika diperlukan
            $user->load(['bank', 'detailUser']);

            return response()->json([
                'status' => 'success',
                'message' => 'User data retrieved successfully',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'bank' => $user->bank,
                    'detail' => $user->detailUser,
                    'email_verified_at' => $user->email_verified_at,
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get user data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation Error.',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()
        ], 201);
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation Error.',
                'errors' => $validator->errors()
            ], 422);
        }

        // logic login
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = User::with('detail_users')->where('email', $request->email)->first();
        // $user = Auth::user()->with('detail_users')->first();

        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'data' => new UserResource($user),
            'access_token' => $token,
            'token_type' => 'Bearer',
            // 'roles' => $user->getRoleNames(),
            // 'permissions' => $user->getAllPermissions()
        ], 200);
    }

    public function logout() {
        Auth::user()->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Token revoked',
        ], 200);
    }
}
