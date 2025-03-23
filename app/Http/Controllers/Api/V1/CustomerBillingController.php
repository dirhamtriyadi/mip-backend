<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\CustomerBilling;
use App\Enums\BillingFollowupEnum;
use App\Http\Resources\Api\V1\CustomerBillingResource;

class CustomerBillingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $search = $request->search;

        // Validasi & format tanggal agar tidak error
        try {
            $start_date = $request->filled('start_date')
                ? Carbon::parse($request->start_date)->format('Y-m-d')
                : Carbon::now()->startOfMonth()->format('Y-m-d');

            $end_date = $request->filled('end_date')
                ? Carbon::parse($request->end_date)->format('Y-m-d')
                : Carbon::now()->format('Y-m-d');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid date format',
            ], 400);
        }

        // Inisialisasi query utama
        $customerBillings = CustomerBilling::query()
            ->with(['customer', 'user', 'latestBillingFollowups'])
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [$start_date, $end_date])
            ->orWhereHas('latestBillingFollowups', function ($q) use ($user, $start_date, $end_date) {
                $q->whereIn('status', ['visit', 'promise_to_pay', 'pay'])
                ->whereHas('customerBilling', function ($q) use ($user, $start_date, $end_date) {
                    $q->whereBetween('date_exec', [$start_date, $end_date])
                        ->where('user_id', $user->id);
                });
            });

        // Jika ada pencarian, tambahkan filter
        if (!empty($search)) {
            $matchingStatuses = BillingFollowupEnum::search($search);
            $matchingValues = array_map(fn ($status) => $status->value, $matchingStatuses);
            if (empty($matchingValues)) {
                $matchingValues = [null]; // Nilai default jika tidak ada hasil pencarian
            }

            $customerBillings->where(function ($query) use ($search, $matchingValues) {
                $query->where('bill_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('name_customer', 'like', "%{$search}%")
                            ->orWhere('no_contract', 'like', "%{$search}%");
                    })
                    ->orWhereHas('latestBillingFollowups', function ($q) use ($matchingValues) {
                        $q->whereIn('status', $matchingValues);
                    });
            });
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully.',
            'data' => CustomerBillingResource::collection($customerBillings->get()),
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
