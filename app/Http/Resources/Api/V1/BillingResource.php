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
            'customer_id' => $this->customer_id,
            'customer' => $this->customer,
            'user_id' => $this->user_id,
            'user' => $this->user,
            // 'status' => $this->status,
            'status' => [
                'label' => $this->status->label(),
                'value' => $this->status->value,
            ],
            // 'billingStatuses' => $this->billingStatuses,
            'billingStatuses' => BillingStatusResource::collection($this->billingStatuses),
            'latestBillingStatus' => new BillingStatusResource($this->latestBillingStatus),
            // 'destination' => $this->destination,
            // 'image_visit' => isset($this->image_visit) ? asset('images/billings/' . $this->image_visit) : null,
            // 'description_visit' => $this->description_visit,
            // 'promise_date' => $this->promise_date,
            // 'image_promise' => isset($this->image_promise) ? asset('images/billings/' . $this->image_promise) : null,
            // 'description_promise' => $this->description_promise,
            // 'amount' => $this->amount,
            // 'image_amount' => isset($this->image_amount) ? asset('images/billings/' . $this->image_amount) : null,
            // 'description_amount' => $this->description_amount,
            // 'signature_officer' => isset($this->signature_officer) ? asset('images/billings/' . $this->signature_officer) : null,
            // 'signature_customer' => isset($this->signature_customer) ? asset('images/billings/' . $this->signature_customer) : null,
        ];
    }
}
