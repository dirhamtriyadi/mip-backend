<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Bank;

class UserController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            // 'auth',
            // new Middleware('subscribed', except: ['store']),
            new Middleware('permission:user.index', only: ['index']),
            new Middleware('permission:user.create', only: ['index', 'create', 'store']),
            new Middleware('permission:user.edit', only: ['index', 'edit', 'update']),
            new Middleware('permission:user.delete', only: ['index', 'destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("users.index");
    }

    // Fetch data for DataTable
    public function fetchDataTable(Request $request)
    {
        // load all users with their detail_users and roles
        $users = User::with('detail_users', 'roles')->get();

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('bank', function ($user) {
                return $user->bank->name ?? '-';
            })
            ->addColumn('nik', function ($user) {
                return $user->detail_users->nik ?? '-';
            })
            ->addColumn('role', function ($user) {
                return $user->roles->pluck('name')->implode(', ');
            })
            ->addColumn('action', function ($user) {
                return view('users.action', ['value' => $user]);
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        $banks = Bank::all();

        return view("users.create", [
            "roles" => $roles,
            "banks" => $banks,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'nik' => 'required|string|max:255|unique:detail_users,nik',
            'bank_id' => 'nullable|exists:banks,id',
            'role' => 'nullable|exists:roles,name',
        ]);

        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = Hash::make($validatedData['password']);
        $user->bank_id = $validatedData['bank_id'];
        $user->save();
        $user->detail_users()->create([
            'nik' => $validatedData['nik'],
        ]);

        if ($request->filled('role')) {
            $user->assignRole($validatedData['role']);
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
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
        $user = User::findOrFail($id);
        $roles = Role::all();
        $userRoles = $user->roles->pluck('name')->toArray();
        $banks = Bank::all();

        return view('users.edit', [
            'user' => $user,
            'roles' => $roles,
            'userRoles' => $userRoles,
            'banks' => $banks,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id)->load('detail_users');
        $detailUserId = $user->detail_users->id ?? null;

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'nik' => 'required|string|max:255|unique:detail_users,nik,' . $detailUserId,
            'bank_id' => 'nullable|exists:banks,id',
            'role' => 'nullable|array',
            'role.*' => 'exists:roles,name',
        ]);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        if ($validatedData['password']) {
            $user->password = Hash::make($validatedData['password']);
        }
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

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
