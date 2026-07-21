<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EcommerceProductResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $branchId = $request->query('branch_id');

        if ($branchId) {
            $availableStock = (int) $this->inventories
                ->where('branch_id', (int) $branchId)
                ->sum('stock_quantity');
        } else {
            $availableStock = (int) ($this->inventories_sum_stock_quantity ?? $this->inventories->sum('stock_quantity'));
        }

        return [
            'id'              => $this->id,
            'sku'             => $this->sku,
            'product_name'    => $this->name,
            'price'           => (float) $this->price,
            'available_stock' => $availableStock,
            'description'     => $this->description,
            'updated_at'      => $this->updated_at?->toIso8601String(),
        ];
    }
}
