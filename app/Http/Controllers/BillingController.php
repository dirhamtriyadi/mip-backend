<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Billing;
use App\Models\User;
use App\Models\Customer;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BillingImport;

class BillingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return view('billings.index', [
            'users' => $users,
        ]);
    }

    // Fetch data for DataTable
    public function fetchDataTable(Request $request)
    {
        // load all billings priority user_id is null and destination is visit
        $billings = Billing::with(['customer', 'user'])
            ->orWhere('user_id', null)
            ->orWhere('destination', 'visit')
            ->get();

        return DataTables::of($billings)
            ->addColumn('select', function ($billing) {
                return '<input type="checkbox" class="checkbox" id="select-' . $billing->id . '" name="checkbox[]" value="' . $billing->id . '">';
            })
            ->addIndexColumn()
            ->addColumn('customer', function ($billing) {
                return $billing->customer->name_customer;
            })
            ->addColumn('user', function ($billing) {
                return $billing->user->name ?? '-';
            })
            ->editColumn('date', function ($billing) {
                return Carbon::parse($billing->date)->format('d-m-Y');
            })
            ->editColumn('destination', function ($billing) {
                return view('billings.destination', ['value' => $billing]);
            })
            ->editColumn('image_visit', function ($billing) {
                return $billing->image_visit ? '<a href="' . asset('images/billings/' . $billing->image_visit) . '" target="_blank">Lihat</a>' : '-';
            })
            ->editColumn('description_visit', function ($billing) {
                return $billing->description_visit ? $billing->description_visit : '-';
            })
            ->editColumn('promise_date', function ($billing) {
                return $billing->promise_date ? Carbon::parse($billing->promise_date)->format('d-m-Y') : '-';
            })
            ->editColumn('image_promise', function ($billing) {
                return $billing->image_promise ? '<a href="' . asset('images/billings/' . $billing->image_promise) . '" target="_blank">Lihat</a>' : '-';
            })
            ->editColumn('description_promise', function ($billing) {
                return $billing->description_promise ? $billing->description_promise : '-';
            })
            ->editColumn('amount', function ($billing) {
                return $billing->amount ? 'Rp ' . number_format($billing->amount, 0, ',', '.') : '-';
            })
            ->editColumn('image_amount', function ($billing) {
                return $billing->image_amount ? '<a href="' . asset('images/billings/' . $billing->image_amount) . '" target="_blank">Lihat</a>' : '-';
            })
            ->editColumn('description_amount', function ($billing) {
                return $billing->description_amount ? $billing->description_amount : '-';
            })
            ->editColumn('signature_officer', function ($billing) {
                return $billing->signature_officer ? '<a href="' . asset('images/billings/' . $billing->signature_officer) . '" target="_blank">Lihat</a>' : '-';
            })
            ->editColumn('signature_customer', function ($billing) {
                return $billing->signature_customer ? '<a href="' . asset('images/billings/' . $billing->signature_customer) . '" target="_blank">Lihat</a>' : '-';
            })
            ->addColumn('action', function ($billing) {
                return view('billings.action', ['value' => $billing]);
            })
            ->addColumn('details', function ($billing) {
                return;
            })
            ->rawColumns(['select', 'destination', 'image_visit', 'image_promise', 'image_amount', 'signature_officer', 'signature_customer', 'action'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $customers = Customer::all();

        return view('billings.create', [
            'users' => $users,
            'customers' => $customers,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'no_billing' => 'required|unique:billings',
            'date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'required|exists:users,id',
            'destination' => 'required|in:visit,promise,pay',
            'image_visit' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description_visit' => 'nullable',
            // 'promise_date' => 'required_if:destination,promise',
            'promise_date' => 'nullable',
            'image_promise' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description_promise' => 'nullable',
            // 'amount' => 'required_if:destination,pay',
            'amount' => 'nullable',
            'image_amount' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description_amount' => 'nullable',
            'signature_officer' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'signature_customer' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $validatedData['created_by'] = auth()->id();

        // save image to public/images/billings and change name to timestamp
        if ($request->hasFile('image_visit')) {
            // save image to public/images/billings and change name file to name user-timestamp
            $file = $request->file('image_visit');
            $fileName = $validatedData['no_billing'] . '-' . 'visit' . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/billings'), $fileName);
            $validatedData['image_visit'] = $fileName;
        }

        // save image to public/images/billings and change name to timestamp
        if ($request->hasFile('image_promise')) {
            // save image to public/images/billings and change name file to name user-timestamp
            $file = $request->file('image_promise');
            $fileName = $validatedData['no_billing'] . '-' . 'promise' . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/billings'), $fileName);
            $validatedData['image_promise'] = $fileName;
        }

        // save image to public/images/billings and change name to timestamp
        if ($request->hasFile('image_amount')) {
            // save image to public/images/billings and change name file to name user-timestamp
            $file = $request->file('image_amount');
            $fileName = $validatedData['no_billing'] . '-' . 'amount' . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/billings'), $fileName);
            $validatedData['image_amount'] = $fileName;
        }

        // save image to public/images/billings and change name to timestamp
        if ($request->hasFile('signature_officer')) {
            // save image to public/images/billings and change name file to name user-timestamp
            $file = $request->file('signature_officer');
            $fileName = $validatedData['no_billing'] . '-' . 'signature_officer' . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/billings'), $fileName);
            $validatedData['signature_officer'] = $fileName;
        }

        // save image to public/images/billings and change name to timestamp
        if ($request->hasFile('signature_customer')) {
            // save image to public/images/billings and change name file to name user-timestamp
            $file = $request->file('signature_customer');
            $fileName = $validatedData['no_billing'] . '-' . 'signature_customer' . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/billings'), $fileName);
            $validatedData['signature_customer'] = $fileName;
        }

        Billing::create($validatedData);

        return redirect()->route('billings.index')->with('success', 'Data berhasil disimpan');
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
        $billing = Billing::findOrFail($id);
        $users = User::all();
        $customers = Customer::all();

        return view('billings.edit', [
            'data' => $billing,
            'users' => $users,
            'customers' => $customers,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'no_billing' => 'required|unique:billings,no_billing,' . $id,
            'date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'required|exists:users,id',
            'destination' => 'required|in:visit,promise,pay',
            'image_visit' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description_visit' => 'nullable',
            // 'promise_date' => 'required_if:destination,promise',
            'promise_date' => 'nullable',
            'image_promise' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description_promise' => 'nullable',
            // 'amount' => 'required_if:destination,pay',
            'amount' => 'nullable',
            'image_amount' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description_amount' => 'nullable',
            'signature_officer' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'signature_customer' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $billing = Billing::findOrFail($id);

        // save image to public/images/billings and change name to timestamp
        if ($request->hasFile('image_visit')) {
            // remove old image
            if ($billing->image_visit != null && file_exists(public_path('images/billings/' . $billing->image_visit))) {
                unlink(public_path('images/billings/' . $billing->image_visit));
            }

            // save image to public/images/billings and change name file to name user-timestamp
            $file = $request->file('image_visit');
            $fileName = $validatedData['no_billing'] . '-' . 'visit' . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/billings'), $fileName);
            $validatedData['image_visit'] = $fileName;
        }

        // save image to public/images/billings and change name to timestamp
        if ($request->hasFile('image_promise')) {
            // remove old image
            if ($billing->image_promise != null && file_exists(public_path('images/billings/' . $billing->image_promise))) {
                unlink(public_path('images/billings/' . $billing->image_promise));
            }

            // save image to public/images/billings and change name file to name user-timestamp
            $file = $request->file('image_promise');
            $fileName = $validatedData['no_billing'] . '-' . 'promise' . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/billings'), $fileName);
            $validatedData['image_promise'] = $fileName;
        }

        // save image to public/images/billings and change name to timestamp
        if ($request->hasFile('image_amount')) {
            // remove old image
            if ($billing->image_amount != null && file_exists(public_path('images/billings/' . $billing->image_amount))) {
                dd(public_path('images/billings/' . $billing->image_amount));
                unlink(public_path('images/billings/' . $billing->image_amount));
            }

            // save image to public/images/billings and change name file to name user-timestamp
            $file = $request->file('image_amount');
            $fileName = $validatedData['no_billing'] . '-' . 'amount' . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/billings'), $fileName);
            $validatedData['image_amount'] = $fileName;
        }

        // save image to public/images/billings and change name to timestamp
        if ($request->hasFile('signature_officer')) {
            // remove old image
            if ($billing->signature_officer != null && file_exists(public_path('images/billings/' . $billing->signature_officer))) {
                unlink(public_path('images/billings/' . $billing->signature_officer));
            }

            // save image to public/images/billings and change name file to name user-timestamp
            $file = $request->file('signature_officer');
            $fileName = $validatedData['no_billing'] . '-' . 'signature_officer' . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/billings'), $fileName);
            $validatedData['signature_officer'] = $fileName;
        }

        // save image to public/images/billings and change name to timestamp
        if ($request->hasFile('signature_customer')) {
            // remove old image
            if ($billing->signature_customer != null && file_exists(public_path('images/billings/' . $billing->signature_customer))) {
                unlink(public_path('images/billings/' . $billing->signature_customer));
            }

            // save image to public/images/billings and change name file to name user-timestamp
            $file = $request->file('signature_customer');
            $fileName = $validatedData['no_billing'] . '-' . 'signature_customer' . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/billings'), $fileName);
            $validatedData['signature_customer'] = $fileName;
        }

        $validatedData['updated_by'] = auth()->id();
        // if ($request->destination == 'visit') {
        //     $validatedData['description_visit'] = null;
        //     $validatedData['promise_date'] = null;
        //     $validatedData['description_promise'] = null;
        //     $validatedData['amount'] = null;
        //     $validatedData['description_amount'] = null;
        //     if ($billing->image_visit != null && file_exists(public_path('images/billings/' . $billing->image_visit))) {
        //         unlink(public_path('images/billings/' . $billing->image_visit));
        //     }
        //     if ($billing->image_promise != null && file_exists(public_path('images/billings/' . $billing->image_promise))) {
        //         unlink(public_path('images/billings/' . $billing->image_promise));
        //     }
        //     if ($billing->image_amount != null && file_exists(public_path('images/billings/' . $billing->image_amount))) {
        //         unlink(public_path('images/billings/' . $billing->image_amount));
        //     }
        //     $validatedData['image_amount'] = null;
        //     if ($billing->siganture_officer != null && file_exists(public_path('images/billings/' . $billing->signature_officer))) {
        //         unlink(public_path('images/billings/' . $billing->signature_officer));
        //     }
        //     $validatedData['signature_officer'] = null;
        //     if ($billing->siganture_customer != null && file_exists(public_path('images/billings/' . $billing->signature_customer))) {
        //         unlink(public_path('images/billings/' . $billing->signature_customer));
        //     }
        //     $validatedData['signature_customer'] = null;
        // }

        $billing->update($validatedData);

        return redirect()->route('billings.index')->with('success', 'Data berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $billing = Billing::findOrFail($id);

        // remove image
        // if (file_exists(public_path('images/billings/' . $billing->image_amount))) {
        //     unlink(public_path('_amounts/billings/' . $billing->image_amount));
        // }

        // if (file_exists(public_path('images/billings/' . $billing->signature_officer))) {
        //     unlink(public_path('_amounts/billings/' . $billing->signature_officer))) {;
        // }

        // if (file_exists(public_path('images/billings/' . $billing->signature_customer))) {
        //     unlink(public_path('_amounts/billings/' . $billing->signature_customer))) {;
        // }

        $billing->deleted_by = auth()->id();
        $billing->save();
        $billing->delete();

        return redirect()->route('billings.index')->with('success', 'Data berhasil dihapus');
    }

    public function reset(string $id)
    {
        $user = auth()->user();
        $billing = Billing::findOrFail($id);

        $billing->destination = 'visit';
        $billing->description_visit = null;
        $billing->promise_date = null;
        $billing->description_promise = null;
        $billing->amount = null;
        $billing->description_amount = null;
        $billing->updated_by = $user->id;
        $billing->deleted_by = null;
        $billing->deleted_at = null;
        // remove image
        if ($billing->image_visit != null && file_exists(public_path('images/billings/' . $billing->image_visit))) {
            unlink(public_path('images/billings/' . $billing->image_visit));
        }
        $billing->image_visit = null;
        if ($billing->image_promise != null && file_exists(public_path('images/billings/' . $billing->image_promise))) {
            unlink(public_path('images/billings/' . $billing->image_promise));
        }
        $billing->image_promise = null;
        if ($billing->image_amount != null && file_exists(public_path('images/billings/' . $billing->image_amount))) {
            unlink(public_path('images/billings/' . $billing->image_amount));
        }
        $billing->image_amount = null;
        if ($billing->signature_officer != null && file_exists(public_path('images/billings/' . $billing->signature_officer))) {
            unlink(public_path('images/billings/' . $billing->signature_officer));
        }
        $billing->signature_officer = null;
        if ($billing->signature_customer != null && file_exists(public_path('images/billings/' . $billing->signature_customer))) {
            unlink(public_path('images/billings/' . $billing->signature_customer));
        }
        $billing->signature_customer = null;

        $billing->save();

        return redirect()->route('billings.index')->with('success', 'Data berhasil direset');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');
        Excel::import(new BillingImport, $file);

        return redirect()->route('billings.index')->with('success', 'Data berhasil diimport');
    }

    public function templateImport()
    {
        $template = public_path('templates/billings.xlsx');

        return response()->download($template);
    }

    public function massDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|exists:billings,id',
        ]);

        $ids = $request->input('ids', []);
        $user = auth()->user();

        foreach ($ids as $id) {
            $billing = Billing::findOrFail($id);
            $billing->deleted_by = $user->id;
            $billing->save();
            $billing->delete();
        }

        return redirect()->route('billings.index')->with('success', 'Data berhasil dihapus');
    }

    public function massReset(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|exists:billings,id',
        ]);

        $ids = $request->input('ids', []);
        $user = auth()->user();

        foreach ($ids as $id) {
            $billing = Billing::findOrFail($id);
            $billing->destination = 'visit';
            $billing->description_visit = null;
            $billing->promise_date = null;
            $billing->description_promise = null;
            $billing->amount = null;
            $billing->description_amount = null;
            $billing->updated_by = $user->id;
            $billing->deleted_by = null;
            $billing->deleted_at = null;
            // remove image
            if ($billing->image_visit != null && file_exists(public_path('images/billings/' . $billing->image_visit))) {
                unlink(public_path('images/billings/' . $billing->image_visit));
            }
            $billing->image_visit = null;
            if ($billing->image_promise != null && file_exists(public_path('images/billings/' . $billing->image_promise))) {
                unlink(public_path('images/billings/' . $billing->image_promise));
            }
            $billing->image_promise = null;
            if ($billing->image_amount != null && file_exists(public_path('images/billings/' . $billing->image_amount))) {
                unlink(public_path('images/billings/' . $billing->image_amount));
            }
            $billing->image_amount = null;
            if ($billing->signature_officer != null && file_exists(public_path('images/billings/' . $billing->signature_officer))) {
                unlink(public_path('images/billings/' . $billing->signature_officer));
            }
            $billing->signature_officer = null;
            if ($billing->signature_customer != null && file_exists(public_path('images/billings/' . $billing->signature_customer))) {
                unlink(public_path('images/billings/' . $billing->signature_customer));
            }
            $billing->signature_customer = null;

            $billing->save();
        }

        return redirect()->route('billings.index')->with('success', 'Data berhasil direset');
    }

    public function massSelectOfficer(Request $request)
    {
        $validatedData = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|exists:billings,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $ids = $request->input('ids', []);
        $user = auth()->user();

        foreach ($ids as $id) {
            $billing = Billing::findOrFail($id);
            $billing->user_id = $validatedData['user_id'];
            $billing->save();
        }

        return redirect()->route('billings.index')->with('success', 'Data berhasil ditandatangani');
    }
}
