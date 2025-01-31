<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Billing;
use App\Models\BillingStatus;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Resources\Api\V1\BillingResource;
use Carbon\Carbon;

class BillingReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
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
            'data' => BillingResource::collection($billings),
        ], 200);
    }

    public function exportPdfByUser(Request $request)
    {
        $user = auth()->user();
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-t');

        $billings = Billing::with(['customer', 'user', 'latestBillingStatus'])
            ->whereBetween('date', [$start_date, $end_date])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

            // dd($billings->toArray());

        $pdf = Pdf::loadView('templates.pdf.billing-reports-pdf', [
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

        $billings = Billing::with(['customer', 'user', 'latestBillingStatus'])
            ->whereBetween('date', [$start_date, $end_date])
            ->where('user_id', $user->id)
            ->where('customer_id', $request->customer_id)
            ->latest()
            ->get();

            dd($billings->toArray());
    }
}
