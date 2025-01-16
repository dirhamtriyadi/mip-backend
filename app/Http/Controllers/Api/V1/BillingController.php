<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Billing;
use App\Models\BillingStatus;
use App\Http\Resources\Api\V1\BillingResource;
use Validator;
use Carbon\Carbon;
use App\Enums\BillingStatusesEnum;

class BillingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search ?? null;
        $start_date = $request->start_date ?? Carbon::now()->startOfMonth();
        $end_date = $request->end_date ?? Carbon::now();

        $user = auth()->user();

        if ($request->start_date && $request->end_date) {
            $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
            $end_date = Carbon::parse($request->end_date)->format('Y-m-d');
        }

        $billings = Billing::with(['customer', 'user', 'latestBillingStatus'])
            ->where('user_id', $user->id)
            ->whereBetween('date', [$start_date, $end_date])
            ->orWhereHas('latestBillingStatus', function($q) use($search, $user, $start_date, $end_date) {
                $q->whereIn('status', ['visit', 'promise_to_pay', 'pay'])
                  ->whereHas('billing', function($q) use($user, $start_date, $end_date) {
                      $q->whereBetween('date', [$start_date, $end_date])
                        ->where('user_id', $user->id);
                  });
            })
            ->orderBy(
                BillingStatus::select('status')
                    ->whereColumn('billing_id', 'billings.id')
                    ->orderBy('created_at', 'desc')
                    ->limit(1)
            )
            ->get();

        if ($search) {
            // Cari enum yang cocok dengan pencarian
            $matchingStatuses = BillingStatusesEnum::search($search);

            // Ambil nilai (`value`) dari hasil pencarian
            $matchingValues = array_map(fn ($status) => $status->value, $matchingStatuses);

            // Pastikan $matchingValues adalah array yang valid untuk query
            if (empty($matchingValues)) {
                $matchingValues = [null]; // Jika tidak ada pencocokan, kita bisa gunakan nilai default
            }
            // dd($matchingValues);

            $billings = Billing::with(['customer', 'user', 'latestBillingStatus'])
            ->where('user_id', $user->id)
            ->whereBetween('date', [$start_date, $end_date])
            ->where(function($query) use ($search, $matchingValues) {
                $query->where('status', 'like', '%' . $search . '%')
                      ->orWhere('no_billing', 'like', '%' . $search . '%')
                      ->orWhereHas('customer', function($q) use ($search) {
                          $q->where('name_customer', 'like', '%' . $search . '%')
                            ->orWhere('no', 'like', '%' . $search . '%');
                      })
                      ->orWhereHas('latestBillingStatus', function($q) use ($matchingValues) {
                          $q->whereIn('status', $matchingValues);
                      });
            })
            ->orderBy(
                BillingStatus::select('status')
                    ->whereColumn('billing_id', 'billings.id')
                    ->orderBy('created_at', 'desc')
                    ->limit(1)
            )
            ->get();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully.',
            'data' => BillingResource::collection($billings)
        ], 200);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = auth()->user();
        $billing = Billing::where('user_id', $user->id)->where('id', $id)->first();

        if (!$billing) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully.',
            'data' => new BillingResource($billing)
        ], 200);
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
