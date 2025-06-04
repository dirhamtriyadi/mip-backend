<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\CustomerBilling;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerBillingImport implements ToModel, WithHeadingRow
{
    private $bank_id;
    private $user_id;

    public function __construct(int $bank_id, int $user_id)
    {
        $this->bank_id = $bank_id;
        $this->user_id = $user_id;
    }

    private function parseDate($dateValue)
    {
        if (empty($dateValue)) return null;

        try {
            return Carbon::createFromFormat('d/m/Y', $dateValue)->format('Y-m-d');
        } catch (\Exception $e) {
            try {
                return Carbon::createFromFormat('d-m-Y', $dateValue)->format('Y-m-d');
            } catch (\Exception $e) {
                try {
                    return Carbon::parse($dateValue)->format('Y-m-d');
                } catch (\Exception $e) {
                    return null;
                }
            }
        }
    }

    public function model(array $row)
    {
        $user = auth()->user();

        // Validasi data wajib
        $noContract = $row['no_contract'] ?? $row['no_kontrak'] ?? null;
        $nameCustomer = $row['name_customer'] ?? $row['nama_nasabah'] ?? null;

        if (empty($noContract) || empty($nameCustomer)) {
            return null; // Skip row jika data utama kosong
        }

        // Customer update or create
        $customer = Customer::updateOrCreate([
            'no_contract' => $noContract,
        ], [
            'no_contract' => $noContract,
            'bank_account_number' => $row['bank_account_number'] ?? $row['no_rekening'] ?? null,
            'name_customer' => $nameCustomer,
            'name_mother' => $row['name_mother'] ?? $row['nama_ibu'] ?? null,
            'phone_number' => $row['phone_number'] ?? $row['no_hp'] ?? $row['hp'] ?? null,
            'bank_id' => $this->bank_id,
            'margin_start' => $row['margin_start'] ?? $row['margin_awal'] ?? null,
            'os_start' => $row['os_start'] ?? $row['os_awal'] ?? null,
            'margin_remaining' => $row['margin_remaining'] ?? $row['margin_sisa'] ?? $row['sisa_margin'] ?? null,
            'installments' => $row['installments'] ?? $row['angsuran'] ?? null,
            'month_arrears' => $row['month_arrears'] ?? $row['tgk_bln'] ?? null,
            'arrears' => $row['arrears'] ?? $row['tunggakan'] ?? null,
            'due_date' => $this->parseDate($row['due_date'] ?? $row['jth_tempo'] ?? null),
            'description' => $row['description'] ?? $row['keterangan'] ?? null,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        // Customer Address
        $customerAddress = $customer->customerAddress()->updateOrCreate([
            'customer_id' => $customer->id,
        ], [
            'address' => $row['address'] ?? $row['alamat'] ?? null,
            'village' => $row['village'] ?? $row['desa'] ?? null,
            'subdistrict' => $row['subdistrict'] ?? $row['kecamatan'] ?? null,
        ]);

        // Generate bill number dengan locking untuk menghindari race condition
        $datePrefix = Carbon::now()->format('Ymd');

        // Customer Billing
        $customerBilling = $customer->customerBilling()->firstOrCreate([
            'customer_id' => $customer->id,
        ], [
            'bill_number' => $this->generateBillNumber($datePrefix),
            'user_id' => $this->user_id,
            'created_by' => $user->id,
        ]);

        // Update jika sudah ada
        if (!$customerBilling->wasRecentlyCreated) {
            $customerBilling->update([
                'user_id' => $this->user_id ?? $row['user_id'] ?? $row['officer_id'] ?? $customerBilling->user_id,
                'updated_by' => $user->id,
            ]);
        }

        return $customerBilling;
    }

    private function generateBillNumber($datePrefix)
    {
        // Lock table untuk menghindari duplicate number
        $lastBill = CustomerBilling::lockForUpdate()
            ->where('bill_number', 'like', "$datePrefix%")
            ->latest('bill_number')
            ->first();

        if ($lastBill) {
            $lastNumber = (int) substr($lastBill->bill_number, -4);
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }

        return $datePrefix . $nextNumber;
    }
}
