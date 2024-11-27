<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("roles.index");
    }

    // Fetch data for DataTable
    public function fetchDataTable(Request $request)
    {
        // load all roles
        $roles = Role::all();

        return DataTables::of($roles)
            ->addIndexColumn()
            ->editColumn('permissions', function ($role) {
                return $role->permissions->pluck('name')->implode(', ');
            })
            ->addColumn('action', function ($role) {
                return view('roles.action', ['value' => $role]);
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all()->groupBy(function($item) {
            return explode('.', $item->name)[0];
        });

        return view("roles.create", [
            "permissions" => $permissions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
        ]);

        $role = new Role();
        $role->name = $validatedData['name'];
        $role->save();

        if ($request->filled('permissions')) {
            $role->syncPermissions($validatedData['permissions']);
        }

        return redirect()->route('roles.index');
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
        $role = Role::findOrFail($id);
        $permissions = Permission::all()->groupBy(function($item) {
            return explode('.', $item->name)[0];
        });
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'permissions' => 'nullable|array',
        ]);

        $role = Role::findOrFail($id);
        $role->name = $validatedData['name'];
        $role->save();

        if ($request->filled('permissions')) {
            $role->syncPermissions($validatedData['permissions']);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('roles.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('roles.index');
    }
}
