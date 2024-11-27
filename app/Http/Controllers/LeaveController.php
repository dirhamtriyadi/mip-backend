<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Carbon;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil data semua cuti yang diajukan oleh user dan mengurutkan berdasarkan status pending dan terlama dibuat
        $leaves = Leave::with('user')
            ->orderBy('status', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('leaves.index', [
            'data' => $leaves,
        ]);
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
                return Carbon\Carbon::parse($leave->start_date)->format('d-m-Y');
            })
            ->editColumn('end_date', function ($leave) {
                return Carbon\Carbon::parse($leave->end_date)->format('d-m-Y');
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
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'required|in:pending,approved,rejected',
            'response' => 'nullable',
        ]);

        Leave::create($validatedData);

        return redirect()->route('leaves.index')->with('success', 'Cuti berhasil diajukan');
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $leave = Leave::findOrFail($id);
        $leave->delete();

        return redirect()->route('leaves.index')->with('success', 'Data berhasil dihapus');
    }

    public function response(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:approved,rejected',
            'response' => 'nullable',
        ]);

        $leave = Leave::findOrFail($id);
        $leave->update($validatedData);

        return redirect()->route('leaves.index')->with('success', 'Data berhasil diubah');
    }
}
