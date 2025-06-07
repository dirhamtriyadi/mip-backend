<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bank;
use App\Http\Resources\Api\V1\BankResource;
use App\Helpers\LoggerHelper;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Display a lsting of the all banks.
     */
    public function allBanks()
    {
        try {
            $banks = Bank::all();

            return response()->json([
                'status' => 'success',
                'message' => 'List of all banks',
                'data' => BankResource::collection($banks),
            ], 200);
        } catch (\Throwable $th) {
            LoggerHelper::logError($th);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve banks',
                'errors' => [
                    'general' => [$th->getMessage()], // atau
                    'exception' => $th->getMessage()
                ]
            ], 500);
        }
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
