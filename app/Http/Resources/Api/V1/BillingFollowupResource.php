<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillingFollowupResource extends JsonResource
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
            'customer_billing_id' => $this->customer_billing_id,
            'status' => [
                'label' => $this->status->label(),
                'value' => $this->status->value,
            ],
            'date_exec' => $this->date_exec,
            'description' => $this->description,
            'proof' => isset($this->proof) ? asset('images/customer-billings/' . $this->proof) : null,
            'promise_date' => isset($this->promise_date) ? $this->promise_date : null,
            'payment_amount' => isset($this->payment_amount) ? $this->payment_amount : null,
            'signature_officer' => isset($this->signature_officer) ? asset('images/customer-billings/' . $this->signature_officer) : null,
            'signature_customer' => isset($this->signature_customer) ? asset('images/customer-billings/' . $this->signature_customer) : null,
        ];
    }
}
