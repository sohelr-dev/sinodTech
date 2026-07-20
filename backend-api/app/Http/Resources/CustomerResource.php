<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'email'              => $this->email,
            'phone'              => $this->phone,
            'last_purchase_at'   => $this->last_purchase_at ? $this->last_purchase_at->toDateTimeString() : null,
            'is_lost'            => $this->isLost(),
            // Use count and sum if already loaded/calculated, otherwise fetch
            'purchase_frequency' => $this->sales_count ?? $this->sales()->count(),
            'total_spent'        => (float) ($this->sales_sum_total_amount ?? $this->sales()->sum('total_amount')),
            'sales'              => SaleResource::collection($this->whenLoaded('sales')),
            'created_at'         => $this->created_at->toDateTimeString(),
        ];
    }
}
