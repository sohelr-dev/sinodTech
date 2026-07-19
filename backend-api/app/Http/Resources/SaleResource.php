<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'invoice_number' => $this->invoice_number,
            'total_amount'   => (float) $this->total_amount,
            'status'         => $this->status,
            'branch'         => $this->whenLoaded('branch', fn() => [
                'id'   => $this->branch->id,
                'name' => $this->branch->name,
            ]),
            'customer'       => $this->whenLoaded('customer', fn() => $this->customer ? [
                'id'    => $this->customer->id,
                'name'  => $this->customer->name,
                'email' => $this->customer->email,
            ] : null),
            'employee'       => $this->whenLoaded('employee', fn() => $this->employee ? [
                'id'   => $this->employee->id,
                'name' => $this->employee->name,
            ] : null),
            'items'          => $this->whenLoaded('items', fn() =>
                $this->items->map(fn($item) => [
                    'product_id'   => $item->product_id,
                    'product_name' => $item->product->name ?? null,
                    'quantity'     => $item->quantity,
                    'unit_price'   => (float) $item->unit_price,
                    'subtotal'     => (float) $item->subtotal,
                ])
            ),
            'created_at'     => $this->created_at->toDateTimeString(),
        ];
    }
}
