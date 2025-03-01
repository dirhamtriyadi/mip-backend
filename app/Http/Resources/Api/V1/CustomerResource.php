<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'no_contact' => $this->no_contact,
            'bank_account_number' => $this->bank_account_number,
            'name_customer' => $this->name_customer,
            'name_mother' => $this->name_mother,
            'status' => $this->status,
            'bank_id' => $this->bank_id,
            'margin_start' => $this->margin_start,
            'os_start' => $this->os_start,
            'margin_remaining' => $this->margin_remaining,
            'installments' => $this->installments,
            'month_arrears' => $this->month_arrears,
            'arrears' => $this->arrears,
            'due_date' => $this->due_date,
            'description' => $this->description,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'bank' => new BankResource($this->whenLoaded('bank')),
            'customerAddress' => new CustomerAddressResource($this->whenLoaded('customerAddress')),
            'customerBilling' => new CustomerBillingResource($this->whenLoaded('customerBilling')),
        ];
    }
}
