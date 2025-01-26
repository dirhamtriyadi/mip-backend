<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Billing;
use Validator;
use App\Http\Resources\Api\V1\BillingResource;

class BillingStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $billing = Billing::where('user_id', $user->id)->where('id', $request->id)->first();

        if (!$billing) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found.',
                'errors' => [
                    'id' => 'Data not found.',
                ],
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'no_billing' => 'required|numeric',
            'status' => 'required|in:visit,promise_to_pay,pay',
            'status_date' => 'required|date',
            // 'bank_account_id' => 'required|exists:bank_accounts,id',
            // 'user_id' => 'required|exists:users,id',
            'description' => 'nullable',
            // 'evidence' => 'nullable',
            // 'evidence' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'promise_date' => 'required_if:status,promise_to_pay',
            'payment_amount' => 'reqired_if:status,pay',
            'signature_officer' => 'nullable',
            'signature_customer' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validatedData = $validator->validated();
        $validatedData['updated_by'] = $user->id;

        // save image to public/images/billings and change name to timestamp
        if ($request->hasFile('evidence')) {
            // remove old image
            if ($billing->evidence != null && file_exists(public_path('images/billings/' . $billing->evidence))) {
                unlink(public_path('images/billings/' . $billing->evidence));
            }

            // save image to public/images/billings and change name file to name user-timestamp
            $file = $request->file('evidence');
            $fileName = $validatedData['no_billing'] . '-' . 'visit' . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/billings'), $fileName);
            $validatedData['evidence'] = $fileName;
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
        $billing->billingStatuses()->updateOrCreate([
            'status' => $validatedData['status'],
            'status_date' => $validatedData['status_date'],
            'description' => $validatedData['description'],
            'evidence' => $validatedData['evidence'] ?? null,
            'promise_date' => $validatedData['promise_date'] ?? null,
            'payment_amount' => $validatedData['payment_amount'] ?? null,
            'signature_officer' => $validatedData['signature_officer'] ?? null,
            'signature_customer' => $validatedData['signature_customer'] ?? null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data updated successfully.',
            'data' => new BillingResource($billing),
        ], 200);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
