<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerBillingResource extends JsonResource
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
            'bill_number' => $this->bill_number,
            'customer_id' => $this->customer_id,
            'customer' => $this->customer,
            'user_id' => $this->user_id,
            'user' => $this->user,
            // 'billingFollowups' => BillingFollowupResource::collection($this->billingFollowups),
            'latestBillingFollowups' => new BillingFollowupResource($this->latestBillingFollowups->first()),
            'created_at' => $this->created_at,
        ];
    }
}
