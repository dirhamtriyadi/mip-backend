<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
use App\Helpers\LoggerHelper;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            // 'auth',
            // new Middleware('subscribed', except: ['store']),
            new Middleware('permission:role.index', only: ['index']),
            new Middleware('permission:role.create', only: ['index', 'create', 'store']),
            new Middleware('permission:role.edit', only: ['index', 'edit', 'update']),
            new Middleware('permission:role.delete', only: ['index', 'destroy']),
        ];
    }

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

        try {
            //code...
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

            return redirect()->route('roles.index')->with('success', 'Data berhasil disimpan');
        } catch (ValidationException $e) {
            LoggerHelper::logError($e);

            // Jika ada error validasi, kembalikan dengan pesan error
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Throwable $th) {
            //throw $th;
            LoggerHelper::logError($th);

            return redirect()->back()->with('error', 'Data gagal disimpan: ' . $th->getMessage());
        }
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

        try {
            //code...
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

            return redirect()->route('roles.index')->with('success', 'Data berhasil diubah');
        } catch (ValidationException $e) {
            LoggerHelper::logError($e);

            // Jika ada error validasi, kembalikan dengan pesan error
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Throwable $th) {
            //throw $th;
            LoggerHelper::logError($th);

            return redirect()->back()->with('error', 'Data gagal diubah: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            //code...
            $role = Role::findOrFail($id);
            $role->delete();

            return redirect()->route('roles.index')->with('success', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            //throw $th;
            LoggerHelper::logError($th);

            return redirect()->back()->with('error', 'Data gagal dihapus: ' . $th->getMessage());
        }
    }
}
