<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use App\Models\Billing;
use App\Models\User;
use App\Models\Customer;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BillingImport;
// use App\Enums\BillingStatusEnum;

class BillingController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            // 'auth',
            // new Middleware('subscribed', except: ['store']),
            new Middleware('permission:penagihan.index', only: ['index']),
            new Middleware('permission:penagihan.create', only: ['index', 'create', 'store']),
            new Middleware('permission:penagihan.edit', only: ['index', 'edit', 'update']),
            new Middleware('permission:penagihan.delete', only: ['index', 'destroy']),
        ];
    }

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
        $billings = Billing::with(['customer', 'user', 'billingStatuses', 'latestBillingStatus'])
            ->orWhere('user_id', null)
            ->orWhere('status', 'pending')
            ->orWhere('status', 'process')
            ->orWhere('status', 'success')
            ->orWhere('status', 'cancel')
            ->orderBy('status', 'asc')
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
        ->editColumn('status', function ($billing) {
            return '<span class="badge badge-' . $billing->status->color() . '">' . $billing->status->label() . '</span>';
        })
        ->addColumn('billingStatuses.status', function ($billing) {
            return optional($billing->billingStatuses->last())->status ? '<span class="badge badge-' . $billing->billingStatuses->last()->status->color() . '">' . $billing->billingStatuses->last()->status->label() . '</span>' : '-';
        })
        ->addColumn('billingStatuses.promise_date', function ($billing) {
            return optional($billing->billingStatuses->last())->promise_date ? Carbon::parse($billing->billingStatuses->last()->promise_date)->format('d-m-Y') : '-';
        })
        ->addColumn('billingStatuses.payment_amount', function ($billing) {
            return optional($billing->billingStatuses->last())->payment_amount ? 'Rp ' . number_format($billing->billingStatuses->last()->payment_amount, 0, ',', '.') : '-';
        })
        ->addColumn('billingStatuses.evidence', function ($billing) {
            return optional($billing->billingStatuses->last())->evidence ? '<a href="' . asset('images/billings/' . $billing->billingStatuses->last()->evidence) . '" target="_blank">Lihat</a>' : '-';
        })
        ->addColumn('billingStatuses.description', function ($billing) {
            return optional($billing->billingStatuses->last())->description ?? '-';
        })
        ->addColumn('billingStatuses.signature_officer', function ($billing) {
            return optional($billing->billingStatuses->last())->signature_officer ? '<a href="' . asset('images/billings/' . $billing->billingStatuses->last()->signature_officer) . '" target="_blank">Lihat</a>' : '-';
        })
        ->addColumn('billingStatuses.signature_customer', function ($billing) {
            return optional($billing->billingStatuses->last())->signature_customer ? '<a href="' . asset('images/billings/' . $billing->billingStatuses->last()->signature_customer) . '" target="_blank">Lihat</a>' : '-';
        })
        ->addColumn('action', function ($billing) {
            return view('billings.action', ['value' => $billing]);
        })
        ->addColumn('details', function ($billing) {
            return;
        })
        ->rawColumns(['select', 'status', 'billingStatuses.status', 'billingStatuses.evidence', 'billingStatuses.signature_officer', 'billingStatuses.signature_customer', 'action'])
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
            'no_billing' => 'nullable|unique:billings',
            'date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'nullable|exists:users,id',
            'status' => 'nullable|in:pending,process,success,cancel',
            // 'destination' => 'required|in:visit,promise,pay',
            // 'image_visit' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'description_visit' => 'nullable',
            // 'promise_date' => 'nullable',
            // 'image_promise' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'description_promise' => 'nullable',
            // 'amount' => 'nullable',
            // 'image_amount' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'description_amount' => 'nullable',
            // 'signature_officer' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'signature_customer' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $customer = Customer::findOrFail($validatedData['customer_id']);
        if ($validatedData['no_billing'] == null) {
            $validatedData['no_billing'] = Carbon::now()->format('YmdHis') . $customer->no;
        }
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
        // $billingStatusesEnum = BillingStatusEnum::class;

        return view('billings.edit', [
            'data' => $billing,
            'users' => $users,
            'customers' => $customers,
            // 'billingStatusesEnum' => $billingStatusesEnum,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'no_billing' => 'nullable|unique:billings,no_billing,' . $id,
            'date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,process,success,cancel',
            // 'destination' => 'required|in:visit,promise,pay',
            // 'image_visit' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'description_visit' => 'nullable',
            // 'promise_date' => 'nullable',
            // 'image_promise' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'description_promise' => 'nullable',
            // 'amount' => 'nullable',
            // 'image_amount' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'description_amount' => 'nullable',
            // 'signature_officer' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'signature_customer' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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

        $customer = Customer::findOrFail($validatedData['customer_id']);
        if ($validatedData['no_billing'] == null) {
            $validatedData['no_billing'] = Carbon::now()->format('YmdHis') . $customer->no;
        }
        $validatedData['updated_by'] = auth()->id();

        $billing->update($validatedData);

        return redirect()->route('billings.index')->with('success', 'Data berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $billing = Billing::findOrFail($id);
        $billing->deleted_by = auth()->id();
        $billing->save();
        $billing->delete();

        return redirect()->route('billings.index')->with('success', 'Data berhasil dihapus');
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
        $template = public_path('templates/billings.xls');

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
