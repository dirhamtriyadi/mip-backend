<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerBilling;
use Validator;
use App\Http\Resources\Api\V1\CustomerBillingResource;

class BillingFollowupController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $customerBilling = CustomerBilling::where('user_id', $user->id)->where('id', $request->id)->first();

        if (!$customerBilling) {
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
            'bill_number' => 'required|numeric',
            'status' => 'required|in:visit,promise_to_pay,pay',
            'date_exec' => 'required|date',
            // 'bank_account_id' => 'required|exists:bank_accounts,id',
            // 'user_id' => 'required|exists:users,id',
            'description' => 'required',
            'proof' => 'nullable',
            // 'proof' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'promise_date' => 'required_if:status,promise_to_pay',
            'payment_amount' => 'required_if:status,pay',
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

        // save image to public/images/customer-billings and change name to timestamp
        if ($request->hasFile('proof')) {
            // remove old image
            if ($customerBilling->proof != null && file_exists(public_path('images/customer-billings/' . $customerBilling->proof))) {
                unlink(public_path('images/customer-billings/' . $customerBilling->proof));
            }

            // save image to public/images/customer-billings and change name file to name user-timestamp
            $file = $request->file('proof');
            $fileName = $validatedData['bill_number'] . '-' . 'visit' . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/customer-billings'), $fileName);
            $validatedData['proof'] = $fileName;
        }

        // save image to public/images/customer-billings and change name to timestamp
        if ($request->hasFile('signature_officer')) {
            // remove old image
            if ($customerBilling->signature_officer != null && file_exists(public_path('images/customer-billings/' . $customerBilling->signature_officer))) {
                unlink(public_path('images/customer-billings/' . $customerBilling->signature_officer));
            }

            // save image to public/images/customer-billings and change name file to name user-timestamp
            $file = $request->file('signature_officer');
            $fileName = $validatedData['bill_number'] . '-' . 'signature_officer' . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/customer-billings'), $fileName);
            $validatedData['signature_officer'] = $fileName;
        }

        // save image to public/images/customer-billings and change name to timestamp
        if ($request->hasFile('signature_customer')) {
            // remove old image
            if ($customerBilling->signature_customer != null && file_exists(public_path('images/customer-billings/' . $customerBilling->signature_customer))) {
                unlink(public_path('images/customer-billings/' . $customerBilling->signature_customer));
            }

            // save image to public/images/customer-billings and change name file to name user-timestamp
            $file = $request->file('signature_customer');
            $fileName = $validatedData['bill_number'] . '-' . 'signature_customer' . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/customer-billings'), $fileName);
            $validatedData['signature_customer'] = $fileName;
        }

        $validatedData['updated_by'] = auth()->id();
        $customerBilling->billingFollowups()->updateOrCreate([
            'user_id' => $user->id,
            'status' => $validatedData['status'],
            'date_exec' => $validatedData['date_exec'],
            'description' => $validatedData['description'],
            'proof' => $validatedData['proof'] ?? null,
            'promise_date' => $validatedData['promise_date'] ?? null,
            'payment_amount' => $validatedData['payment_amount'] ?? null,
            'signature_officer' => $validatedData['signature_officer'] ?? null,
            'signature_customer' => $validatedData['signature_customer'] ?? null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data updated successfully.',
            'data' => new CustomerBillingResource($customerBilling),
        ], 200);
    }
}
