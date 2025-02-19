<?php

namespace App\Imports;

use App\Models\Billing;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Customer;
use Carbon\Carbon;

class CustomerBillingImport implements ToModel, WithHeadingRow
{
    public function __construct(int $bank_id)
    {
        $this->bank_id = $bank_id;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $user = auth()->user();

        // Check if customer already exists if not create new customer
        $customer = Customer::updateOrCreate([
            'no_contract' => $row['no_contract'] ?? $row['no_kontrak'],
        ], [
            'bank_account_number' => $row['bank_account_number'] ?? $row['no_rekening'] ?? null,
            'name_customer' => $row['name_customer'] ?? $row['nama_nasabah'],
            'name_mother ' => $row['name_mother'] ?? $row['nama_ibu'] ?? null,
            'phone_number' => $row['phone_number'] ?? $row['no_hp'] ?? $row['hp'] ?? null,
            'bank_id' => $this->bank_id,
            'os_start' => $row['os_start'] ?? $row['os_awal'] ?? null,
            'os_remaining' => $row['os_remaining'] ?? $row['os_sisa'] ?? null,
            'os_total' => $row['os_total'] ?? null,
            'monthly_installments' => $row['monthly_installments'] ?? $row['angsuran'] ?? null,
            'description' => $row['description'] ?? $row['keterangan'] ?? null,
            'created_by' => $user->id,
        ]);

        $customer->customerAddress->updateOrCreate([
            'customer_id' => $row['id'] ?? $customer->id,
        ], [
            'address' => $row['address'] ?? $row['alamat'] ?? null,
            'village' => $row['village'] ?? $row['desa'] ?? null,
            'subdistrict' => $row['subdistrict'] ?? $row['kecamatan'] ?? null,
        ]);

        $customer->customerBilling->updateOrCreate([
            'customer_id' => $row['id'] ?? $customer->id,
        ], [
            'bill_number' => Carbon::now()->format('YmdHis') . $customer->no,
            'customer_id' => $customer->id,
            'user_id' => $row['user_id'] ?? $row['officer_id'] ?? null,
            'created_by' => $user->id,
        ]);
    }
}
