<?php

namespace App\Imports;

use App\Models\Billing;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Customer;
use Carbon\Carbon;
use App\Models\CustomerBilling;

class CustomerBillingImport implements ToModel, WithHeadingRow
{
    public function __construct(int $bank_id, int $user_id)
    {
        $this->bank_id = $bank_id;
        $this->user_id = $user_id;
    }

    private function parseDate($dateValue)
    {
        if (empty($dateValue)) return null;

        try {
            // Coba format dengan slash (10/05/2026)
            return Carbon::createFromFormat('d/m/Y', $dateValue)->format('Y-m-d');
        } catch (\Exception $e) {
            try {
                // Coba format dengan dash (10-05-2026)
                return Carbon::createFromFormat('d-m-Y', $dateValue)->format('Y-m-d');
            } catch (\Exception $e) {
                try {
                    // Fallback ke Carbon::parse untuk format lain
                    return Carbon::parse($dateValue)->format('Y-m-d');
                } catch (\Exception $e) {
                    // Jika semua gagal, return null
                    return null;
                }
            }
        }
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
            'no_contract' => $row['no_contract'] ?? $row['no_kontrak'],
            'bank_account_number' => $row['bank_account_number'] ?? $row['no_rekening'] ?? null,
            'name_customer' => $row['name_customer'] ?? $row['nama_nasabah'],
            'name_mother' => $row['name_mother'] ?? $row['nama_ibu'] ?? null,
            'phone_number' => $row['phone_number'] ?? $row['no_hp'] ?? $row['hp'] ?? null,
            'bank_id' => $this->bank_id,
            'margin_start' => $row['margin_start'] ?? $row['margin_awal'] ?? null,
            'os_start' => $row['os_start'] ?? $row['os_awal'] ?? null,
            'margin_remaining' => $row['margin_remaining'] ?? $row['margin_sisa'] ?? $row['sisa_margin'] ??  null,
            'installments' => $row['installments'] ?? $row['angsuran'] ?? null,
            'month_arrears' => $row['month_arrears'] ?? $row['tgk_bln'] ?? null,
            'arrears' => $row['arrears'] ?? $row['tunggakan'] ?? null,
            'due_date' => $this->parseDate($row['due_date'] ?? $row['jth_tempo'] ?? null),
            // 'due_date' => isset($row['due_date']) && !empty($row['due_date'])
            //     ? Carbon::createFromFormat('d/m/Y', $row['due_date'])->format('Y-m-d')
            //     : (isset($row['jth_tempo']) && !empty($row['jth_tempo'])
            //         ? Carbon::createFromFormat('d/m/Y', $row['jth_tempo'])->format('Y-m-d')
            //         : null),
            // 'due_date' => isset($row['due_date']) && !empty($row['due_date'])
            //     ? Carbon::parse($row['due_date'])->format('Y-m-d')
            //     : (isset($row['jth_tempo']) && !empty($row['jth_tempo'])
            //         ? Carbon::parse($row['jth_tempo'])->format('Y-m-d')
            //         : null),
            'description' => $row['description'] ?? $row['keterangan'] ?? null,
            'created_by' => $user->id,
        ]);

        $customerAddress = $customer->customerAddress()->firstOrCreate([
            'customer_id' => $customer->id,
        ]);

        $customerAddress->update([
            'address' => $row['address'] ?? $row['alamat'] ?? null,
            'village' => $row['village'] ?? $row['desa'] ?? null,
            'subdistrict' => $row['subdistrict'] ?? $row['kecamatan'] ?? null,
        ]);

        $datePrefix = Carbon::now()->format('Ymd'); // YYYYMMDD
        $lastBill = CustomerBilling::where('bill_number', 'like', "$datePrefix%")
            ->latest('bill_number')
            ->first();

        if ($lastBill) {
            // Ambil nomor terakhir dan tambahkan 1
            $lastNumber = (int) substr($lastBill->bill_number, -4);
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // Jika belum ada, mulai dari 0001
            $nextNumber = '0001';
        }

        // Buatkan Customer Billing baru jika nomor kontrak tidak sama dengan import yang baru
        $customerBilling = $customer->customerBilling()->firstOrCreate([
            'customer_id' => $customer->id,
        ], [
            'bill_number' => $datePrefix . $nextNumber,
        ]);

        $customerBilling->update([
            'user_id' => $this->user_id ?? $row['user_id'] ?? $row['officer_id'] ?? null,
            'created_by' => $user->id,
        ]);
    }
}
