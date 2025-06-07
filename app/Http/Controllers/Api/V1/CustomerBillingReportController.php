<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerBilling;
use App\Models\BillingFollowup;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Resources\Api\V1\CustomerBillingResource;
use Carbon\Carbon;
use App\Enums\BillingFollowupEnum;
use App\Helpers\LoggerHelper;

class CustomerBillingReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            //code...
            $search = $request->search ?? null;
            $user = auth()->user();

            if ($request->start_date && $request->end_date) {
                $start_date = Carbon::parse($request->start_date)->startOfDay()->format('Y-m-d H:i:s');
                $end_date = Carbon::parse($request->end_date)->endOfDay()->format('Y-m-d H:i:s');
            } else {
                $start_date = Carbon::now()->startOfMonth()->startOfDay()->format('Y-m-d H:i:s');
                $end_date = Carbon::now()->endOfMonth()->endOfDay()->format('Y-m-d H:i:s');
            }

            // Query yang diperbaiki
            $customerBillingsQuery = CustomerBilling::with(['customer', 'user', 'billingFollowups'])
                ->where('user_id', $user->id)
                // ->whereBetween('created_at', [$start_date, $end_date])
                ->where(function($query) use ($start_date, $end_date) {
                    // Data yang memiliki billing followups dalam rentang tanggal
                    $query->whereHas('billingFollowups', function($q) use ($start_date, $end_date) {
                        $q->whereBetween('created_at', [$start_date, $end_date]);
                    })
                    // ATAU data yang memiliki latest billing followups dengan status tertentu
                    ->orWhereHas('latestBillingFollowups', function($q) use ($start_date, $end_date) {
                        $q->whereIn('status', ['visit', 'promise_to_pay', 'pay'])
                        ->whereBetween('created_at', [$start_date, $end_date]);
                    });
                });

            // Jika ada pencarian
            if ($search) {
                $matchingStatuses = BillingFollowupEnum::search($search);
                $matchingValues = array_map(fn ($status) => $status->value, $matchingStatuses);

                if (empty($matchingValues)) {
                    $matchingValues = [null];
                }

                $customerBillingsQuery->where(function($query) use ($search, $matchingValues, $start_date, $end_date) {
                    $query->where('bill_number', 'like', '%' . $search . '%')
                        ->orWhereHas('customer', function($q) use ($search) {
                            $q->where('name_customer', 'like', '%' . $search . '%')
                                ->orWhere('no_contract', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('billingFollowups', function($q) use ($matchingValues, $start_date, $end_date) {
                            $q->whereIn('status', $matchingValues)
                                ->whereBetween('created_at', [$start_date, $end_date]);
                        });
                });
            }

            $customerBillings = $customerBillingsQuery
                ->orderBy(
                    BillingFollowup::select('created_at')
                        ->whereColumn('customer_billing_id', 'customer_billings.id')
                        ->orderBy('created_at', 'desc')
                        ->limit(1),
                    'desc'
                )
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Data retrieved successfully.',
                'data' => CustomerBillingResource::collection($customerBillings),
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            LoggerHelper::logError($th);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve data.',
                'errors' => [
                    'general' => [$th->getMessage()], // atau
                    'exception' => $th->getMessage()
                ]
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        try {
            //code...
            $user = auth()->user();
            $customerBilling = CustomerBilling::with(['customer', 'customer.customerAddress', 'billingFollowups'])->where('user_id', $user->id)->where('id', $id)->first();

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
        } catch (\Throwable $th) {
            //throw $th;
            LoggerHelper::logError($th);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve data.',
                'errors' => [
                    'general' => [$th->getMessage()], // atau
                    'exception' => $th->getMessage()
                ]
            ], 500);
        }
    }

    public function exportPdfByUser(Request $request)
    {

        try {
            //code...
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
        } catch (\Throwable $th) {
            //throw $th;
            LoggerHelper::logError($th);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to export PDF.',
                'errors' => [
                    'general' => [$th->getMessage()], // atau
                    'exception' => $th->getMessage()
                ]
            ], 500);
        }
    }

    public function exportPdfByCustomer(Request $request)
    {

        try {
            //code...
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
            } catch (\Throwable $th) {
                //throw $th;
                LoggerHelper::logError($th);

                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to export PDF.',
                    'errors' => [
                        'general' => [$th->getMessage()], // atau
                        'exception' => $th->getMessage()
                    ]
                ], 500);
            }
    }
}
