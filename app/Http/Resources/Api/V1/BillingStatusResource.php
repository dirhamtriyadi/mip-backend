<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillingStatusResource extends JsonResource
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
            'billing_id' => $this->billing_id,
            // 'status' => $this->status,
            'status' => [
                'label' => $this->status->label(),
                'value' => $this->status->value,
            ],
            'status_date' => $this->status_date,
            'description' => $this->description,
            'evidence' => isset($this->evidence) ? asset('images/billings/' . $this->evidence) : null,
            'promise_date' => isset($this->promise_date) ? $this->promise_date : null,
            'payment_amount' => isset($this->payment_amount) ? $this->payment_amount : null,
            'signature_officer' => isset($this->signature_officer) ? asset('images/billings/' . $this->signature_officer) : null,
            'signature_customer' => isset($this->signature_customer) ? asset('images/billings/' . $this->signature_customer) : null,
        ];
    }
}
