<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\CustomerBilling;
use App\Enums\BillingFollowupEnum;
use App\Http\Resources\Api\V1\CustomerBillingResource;
use App\Models\BillingFollowup;

class CustomerBillingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search ?? null;
        $user = auth()->user();

        // Format tanggal
        if ($request->start_date && $request->end_date) {
            $start_date = Carbon::parse($request->start_date)->startOfDay();
            $end_date = Carbon::parse($request->end_date)->endOfDay();
        } else {
            $start_date = Carbon::now()->startOfMonth()->startOfDay();
            $end_date = Carbon::now()->endOfDay();
        }

        // Query builder dengan approach yang lebih clean
        $query = CustomerBilling::with(['customer', 'user', 'billingFollowups', 'latestBillingFollowups'])
            ->where('user_id', $user->id)
            ->where(function($q) use ($start_date, $end_date) {
                // Customer billing yang tidak memiliki followup (prioritas utama)
                $q->whereDoesntHave('billingFollowups')
                // ATAU yang dibuat dalam rentang tanggal
                ->orWhereBetween('created_at', [$start_date, $end_date])
                // ATAU yang memiliki followup terbaru dalam rentang tanggal
                ->orWhereHas('latestBillingFollowups', function($subQ) use ($start_date, $end_date) {
                    $subQ->whereIn('status', ['visit', 'promise_to_pay', 'pay'])
                        ->whereBetween('created_at', [$start_date, $end_date]);
                });
            });

        // Apply search filter jika ada
        if ($search) {
            $matchingStatuses = BillingFollowupEnum::search($search);
            $matchingValues = !empty($matchingStatuses)
                ? array_map(fn($status) => $status->value, $matchingStatuses)
                : [];

            $query->where(function($q) use ($search, $matchingValues, $start_date, $end_date) {
                $q->where('bill_number', 'like', "%{$search}%")
                ->orWhereHas('customer', function($customerQ) use ($search) {
                    $customerQ->where('name_customer', 'like', "%{$search}%")
                            ->orWhere('no_contract', 'like', "%{$search}%");
                });

                // Tambahkan search by status jika ada matching values
                if (!empty($matchingValues)) {
                    $q->orWhereHas('latestBillingFollowups', function($followupQ) use ($matchingValues, $start_date, $end_date) {
                        $followupQ->whereIn('status', $matchingValues)
                                ->whereBetween('created_at', [$start_date, $end_date]);
                    });
                }
            });
        }

        // Get results dengan ordering - prioritas billing tanpa followup
        $customerBillings = $query
            ->orderByRaw('CASE WHEN (SELECT COUNT(*) FROM billing_followups WHERE customer_billing_id = customer_billings.id) = 0 THEN 0 ELSE 1 END')
            ->orderByDesc(
                BillingFollowup::select('created_at')
                    ->whereColumn('customer_billing_id', 'customer_billings.id')
                    ->latest('created_at')
                    ->limit(1)
            )
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully.',
            'data' => CustomerBillingResource::collection($customerBillings),
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = auth()->user();
        $customerBilling = CustomerBilling::with('customer', 'customer.customerAddress')->where('user_id', $user->id)->where('id', $id)->first();

        if (!$customerBilling) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found.',
                'errors' => [
                    'id' => 'Data not found.',
                ],
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully.',
            'data' => new CustomerBillingResource($customerBilling),
        ], 200);
    }
}
