<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerBilling;
use App\Models\BillingFollowup;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Resources\Api\V1\CustomerBillingResource;
use Carbon\Carbon;
use App\Models\BillingFollowupEnum;

class CustomerBillingReportController extends Controller
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

        $customerBillings = CustomerBilling::with(['customer', 'user', 'billingFollowups'])
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [$start_date, $end_date])
            ->orWhereHas('billingFollowups', function($q) use($search, $user, $start_date, $end_date) {
                $q->whereIn('status', ['visit', 'promise_to_pay', 'pay'])
                  ->whereHas('customerBilling', function($q) use($user, $start_date, $end_date) {
                      $q->whereBetween('created_at', [$start_date, $end_date])
                        ->where('user_id', $user->id);
                  });
            })
            ->orderBy(
                BillingFollowup::select('status')
                    ->whereColumn('customer_billing_id', 'customer_billings.id')
                    ->orderBy('created_at', 'desc')
                    ->limit(1)
            )
            ->get();

        if ($search) {
            // Cari enum yang cocok dengan pencarian
            $matchingStatuses = BillingFollowupEnum::search($search);

            // Ambil nilai (`value`) dari hasil pencarian
            $matchingValues = array_map(fn ($status) => $status->value, $matchingStatuses);

            // Pastikan $matchingValues adalah array yang valid untuk query
            if (empty($matchingValues)) {
                $matchingValues = [null]; // Jika tidak ada pencocokan, kita bisa gunakan nilai default
            }
            // dd($matchingValues);

            $customerBillings = CustomerBilling::with(['customer', 'user', 'latestBillingFollowups'])
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [$start_date, $end_date])
            ->where(function($query) use ($search, $matchingValues) {
                $query->where('bill_number', 'like', '%' . $search . '%')
                        ->orWhereHas('customer', function($q) use ($search) {
                            $q->where('name_customer', 'like', '%' . $search . '%')
                            ->orWhere('no_contract', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('latestBillingFollowups', function($q) use ($matchingValues) {
                            $q->whereIn('status', $matchingValues);
                        });
            })
            ->get();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully.',
            'data' => CustomerBillingResource::collection($customerBillings),
        ], 200);
    }

    public function exportPdfByUser(Request $request)
    {
        $user = auth()->user();
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-t');

        $billings = CustomerBilling::with(['customer', 'user', 'latestBillingFollowups'])
            ->whereBetween('created_at', [$start_date, $end_date])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

            // dd($billings->toArray());

        $pdf = Pdf::loadView('templates.pdf.customer-billing-reports-pdf', [
            'user' => $user,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'data' => $billings,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('pdf');
    }

    public function exportPdfByCustomer(Request $request)
    {
        $user = auth()->user();
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-t');

        $billings = CustomerBilling::with(['customer', 'user', 'latestBillingFollowups'])
            ->whereBetween('created_at', [$start_date, $end_date])
            ->where('user_id', $user->id)
            ->where('customer_id', $request->customer_id)
            ->latest()
            ->get();

            dd($billings->toArray());
    }
}
