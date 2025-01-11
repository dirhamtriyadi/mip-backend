<?php

namespace App\Imports;

use App\Models\Billing;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Customer;
use Carbon\Carbon;

class BillingImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $user = auth()->user();

        // Check if customer already exists if not create new customer
        $customer = Customer::firstOrCreate([
            'no' => $row['no'] ?? $row['nokontrak'],
        ], [
            'name_customer' => $row['name_customer'] ?? $row['nama'],
            'phone_number' => $row['phone_number'] ?? $row['nohp'] ?? $row['hp'] ?? null,
            'address' => $row['address'] ?? $row['alamat'],
            'bank_id' => $row['bank_id'] ?? $row['bank'] ?? null,
            'date' => $row['date'] ?? $row['tglkontrak'] ?? $row['tgleff'],
            'total_bill' => $row['osmdlc'] + $row['osmgnc'],
            'installment' => $row['installment'] ?? $row['angsmdl'],
            'created_by' => $user->id,
        ]);

        return new Billing([
            'no_billing' => Carbon::now()->format('YmdHis') . $customer->no,
            'date' => Carbon::now(),
            'customer_id' => $customer->id,
            'user_id' => $row['user_id'] ?? $row['officer_id'] ?? null,
            'created_by' => $user->id,
        ]);
    }
}
