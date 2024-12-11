<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillingResource extends JsonResource
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
            'no_billing' => $this->no_billing,
            'date' => $this->date,
            'bank_account_id' => $this->bank_account_id,
            'bank_account' => $this->bankAccount,
            'user_id' => $this->user_id,
            'user' => $this->user,
            'destination' => $this->destination,
            'result' => $this->result,
            'promise_date' => $this->promise_date,
            'amount' => $this->amount,
            'image_amount' => isset($this->image_amount) ? asset('images/billings/' . $this->image_amount) : null,
            'signature_officer' => isset($this->signature_officer) ? asset('images/billings/' . $this->signature_officer) : null,
            'signature_customer' => isset($this->signature_customer) ? asset('images/billings/' . $this->signature_customer) : null,
        ];
    }
}
