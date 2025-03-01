<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Bank;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::findOrFail(auth()->id());
        $banks = Bank::all();
        $roles = Role::all();
        $userRoles = $user->roles->pluck('name')->toArray();

        return view('profile.index', [
            'user' => $user,
            'banks' => $banks,
            'roles' => $roles,
            'userRoles' => $userRoles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
            'nik' => 'required|string|max:255|unique:detail_users,nik,' . auth()->id(),
            'bank_id' => 'nullable|exists:banks,id',
            'role' => 'nullable|array',
            'role.*' => 'exists:roles,name',
        ]);

        $user = User::findOrFail(auth()->id());
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->bank_id = $validatedData['bank_id'];
        $user->save();
        $user->detail_users()->updateOrCreate(
            ['user_id' => $user->id],
            ['nik' => $validatedData['nik']]
        );

        if ($request->filled('role')) {
            $user->syncRoles($validatedData['role']);
        } else {
            $user->syncRoles([]);
        }

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $validatedData = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::findOrFail(auth()->id());
        $user->password = Hash::make($validatedData['password']);
        $user->save();

        return redirect()->route('profile.index')->with('success', 'Password updated successfully.');
    }
}
