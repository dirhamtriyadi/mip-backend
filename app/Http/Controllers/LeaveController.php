<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Helpers\LoggerHelper;
use Illuminate\Validation\ValidationException;

class LeaveController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            // 'auth',
            // new Middleware('subscribed', except: ['store']),
            new Middleware('permission:cuti.index', only: ['index']),
            new Middleware('permission:cuti.create', only: ['index', 'create', 'store']),
            new Middleware('permission:cuti.edit', only: ['index', 'edit', 'update']),
            new Middleware('permission:cuti.delete', only: ['index', 'destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('leaves.index');
    }

    // Fetch data for DataTable
    public function fetchDataTable(Request $request)
    {
        // load all leaves with their user
        $leaves = Leave::with('user')
            ->orderBy('status', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        return DataTables::of($leaves)
            ->addIndexColumn()
            ->addColumn('user', function ($leave) {
                return $leave->user->name;
            })
            ->editColumn('start_date', function ($leave) {
                return Carbon::parse($leave->start_date)->format('d-m-Y');
            })
            ->editColumn('end_date', function ($leave) {
                return Carbon::parse($leave->end_date)->format('d-m-Y');
            })
            ->addColumn('status', function ($leave) {
                return view('leaves.status', ['value' => $leave]);
            })
            ->addColumn('action', function ($leave) {
                return view('leaves.action', ['value' => $leave]);
            })
            ->rawColumns(['status', 'action'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();

        return view('leaves.create', [
            'users' => $users,
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
                'user_id' => 'required|exists:users,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'status' => 'required|in:pending,approved,rejected',
                'response' => 'nullable',
            ]);

            Leave::create($validatedData);

            return redirect()->route('leaves.index')->with('success', 'Cuti berhasil diajukan');
        } catch (ValidationException $e) {
            LoggerHelper::logError($e);

            // Jika ada error validasi, kembalikan dengan pesan error
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Throwable $th) {
            //throw $th;
            LoggerHelper::logError($th);

            return redirect()->back()->withErrors(['general' => 'Failed to submit leave: ' . $th->getMessage()])->withInput();
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
        $leave = Leave::findOrFail($id);
        $users = User::all();

        return view('leaves.edit', [
            'data' => $leave,
            'users' => $users,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            //code...
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'status' => 'required|in:pending,approved,rejected',
                'response' => 'nullable',
            ]);

            $leave = Leave::findOrFail($id);
            $leave->update($validatedData);

            return redirect()->route('leaves.index')->with('success', 'Data berhasil diubah');
        } catch (ValidationException $e) {
            LoggerHelper::logError($e);

            // Jika ada error validasi, kembalikan dengan pesan error
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Throwable $th) {
            //throw $th;
            LoggerHelper::logError($th);

            return redirect()->back()->withErrors(['general' => 'Failed to update leave: ' . $th->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            //code...
            $leave = Leave::findOrFail($id);
            $leave->delete();

            return redirect()->route('leaves.index')->with('success', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            //throw $th;
            LoggerHelper::logError($th);

            return redirect()->back()->withErrors(['general' => 'Failed to delete leave: ' . $th->getMessage()])->withInput();
        }
    }

    public function response(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:approved,rejected',
            'response' => 'nullable',
        ]);

        try {
            //code...
            $leave = Leave::findOrFail($id);
            $leave->update($validatedData);

            return redirect()->route('leaves.index')->with('success', 'Data berhasil diubah');
        } catch (\Throwable $th) {
            //throw $th;
            LoggerHelper::logError($th);

            return redirect()->back()->withErrors(['general' => 'Failed to update leave response: ' . $th->getMessage()])->withInput();
        }
    }
}
