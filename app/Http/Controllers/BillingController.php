<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Billing;
use App\Models\User;
use App\Models\BankAccount;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class BillingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('billings.index');
    }

    // Fetch data for DataTable
    public function fetchDataTable(Request $request)
    {
        // load all billings
        $billings = Billing::all();

        return DataTables::of($billings)
            ->addIndexColumn()
            ->addColumn('bank_account', function ($billing) {
                return $billing->bankAccount->name_customer;
            })
            ->addColumn('user', function ($billing) {
                return $billing->user->name;
            })
            ->editColumn('date', function ($billing) {
                return Carbon::parse($billing->date)->format('d-m-Y');
            })
            ->editColumn('destination', function ($billing) {
                return view('billings.destination', ['value' => $billing]);
            })
            ->editColumn('result', function ($billing) {
                return $billing->result ? $billing->result : '-';
            })
            ->editColumn('amount', function ($billing) {
                return $billing->amount ? 'Rp ' . number_format($billing->amount, 0, ',', '.') : '-';
            })
            ->editColumn('promise_date', function ($billing) {
                return $billing->promise_date ? Carbon::parse($billing->promise_date)->format('d-m-Y') : '-';
            })
            ->editColumn('image_amount', function ($billing) {
                return $billing->image_amount ? '<a href="' . asset('images/billings/' . $billing->image_amount) . '" target="_blank">Lihat</a>' : '-';
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
            ->rawColumns(['destination', 'image_amount', 'signature_officer', 'signature_customer', 'action'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $bankAccounts = BankAccount::all();

        return view('billings.create', [
            'users' => $users,
            'bankAccounts' => $bankAccounts,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'no_billing' => 'required|unique:billings',
                'date' => 'required|date',
                'bank_account_id' => 'required|exists:bank_accounts,id',
                'user_id' => 'required|exists:users,id',
                'destination' => 'required|in:visit,promise,pay',
                'result' => 'nullable',
                'promise_date' => 'required_if:destination,promise',
                'amount' => 'required_if:destination,pay',
                'image_amount' => 'required_if:destination,pay|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'signature_officer' => 'required_if:destination,promise,pay|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'signature_customer' => 'required_if:destination,promise,pay|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $validatedData['created_by'] = auth()->id();

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
        } catch (\Throwable $th) {
            //throw $th;
            if ($request->hasFile('image_amount')) {
                unlink(public_path('images/billings/' . $fileName));
            }
            if ($request->hasFile('signature_officer')) {
                unlink(public_path('images/billings/' . $fileName));
            }
            if ($request->hasFile('signature_customer')) {
                unlink(public_path('images/billings/' . $fileName));
            }

            return redirect()->route('billings.index')->with('error', 'Data gagal disimpan');
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
        $billing = Billing::findOrFail($id);
        $users = User::all();
        $bankAccounts = BankAccount::all();

        return view('billings.edit', [
            'data' => $billing,
            'users' => $users,
            'bankAccounts' => $bankAccounts,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $request->validate([
                'no_billing' => 'required|unique:billings,no_billing,' . $id,
                'date' => 'required|date',
                'bank_account_id' => 'required|exists:bank_accounts,id',
                'user_id' => 'required|exists:users,id',
                'destination' => 'required|in:visit,promise,pay',
                'result' => 'nullable',
                'promise_date' => 'required_if:destination,promise',
                'amount' => 'required_if:destination,pay',
                'image_amount' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'signature_officer' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'signature_customer' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $billing = Billing::findOrFail($id);

            // save image to public/images/billings and change name to timestamp
            if ($request->hasFile('image_amount')) {
                // remove old image
                if (file_exists(public_path('images/billings/' . $billing->image_amount))) {
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
                if (file_exists(public_path('images/billings/' . $billing->signature_officer))) {
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
                if (file_exists(public_path('images/billings/' . $billing->signature_customer))) {
                    unlink(public_path('images/billings/' . $billing->signature_customer));
                }

                // save image to public/images/billings and change name file to name user-timestamp
                $file = $request->file('signature_customer');
                $fileName = $validatedData['no_billing'] . '-' . 'signature_customer' . '-' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/billings'), $fileName);
                $validatedData['signature_customer'] = $fileName;
            }

            $validatedData['updated_by'] = auth()->id();
            if ($request->destination == 'visit') {
                $validatedData['result'] = null;
                $validatedData['promise_date'] = null;
                $validatedData['amount'] = null;
                if ($billing->image_amount != null && file_exists(public_path('images/billings/' . $billing->image_amount))) {
                    unlink(public_path('images/billings/' . $billing->image_amount));
                }
                $validatedData['image_amount'] = null;
                if ($billing->siganture_officer != null && file_exists(public_path('images/billings/' . $billing->signature_officer))) {
                    unlink(public_path('images/billings/' . $billing->signature_officer));
                }
                $validatedData['signature_officer'] = null;
                if ($billing->siganture_customer != null && file_exists(public_path('images/billings/' . $billing->signature_customer))) {
                    unlink(public_path('images/billings/' . $billing->signature_customer));
                }
                $validatedData['signature_customer'] = null;
            }

            $billing->update($validatedData);

            return redirect()->route('billings.index')->with('success', 'Data berhasil diperbarui');
        } catch (\Throwable $th) {
            //throw $th;
            if ($request->hasFile('image_amount')) {
                unlink(public_path('images/billings/' . $fileName));
            }
            if ($request->hasFile('signature_officer')) {
                unlink(public_path('images/billings/' . $fileName));
            }
            if ($request->hasFile('signature_customer')) {
                unlink(public_path('images/billings/' . $fileName));
            }

            return redirect()->route('billings.index')->with('error', 'Data gagal diperbarui');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
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
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('billings.index')->with('error', 'Data gagal dihapus');
        }
    }
}
