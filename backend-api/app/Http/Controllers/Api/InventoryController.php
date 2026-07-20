<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateInventoryRequest;
use App\Models\Inventory;
use Illuminate\Http\JsonResponse;

/**
 * InventoryController
 */
class InventoryController extends Controller
{
    /**
     * List all inventory items.
     */
    public function index(): JsonResponse
    {
        $inventory = Inventory::with('product', 'branch')
            ->orderBy('branch_id')
            ->get()
            ->map(fn($item) => [
                'id'             => $item->id,
                'product_id'     => $item->product_id,
                'product_name'   => $item->product->name ?? '—',
                'product_sku'    => $item->product->sku ?? '—',
                'branch_id'      => $item->branch_id,
                'branch_name'    => $item->branch->name ?? '—',
                'stock_quantity' => $item->stock_quantity,
            ]);

        return response()->json(['data' => $inventory]);
    }

    /**
     * Adjust stock for a specific inventory row.
     */
    public function update(UpdateInventoryRequest $request, int $id): JsonResponse
    {
        $inventory = Inventory::with('product', 'branch')->findOrFail($id);
        $adjustment = $request->integer('adjustment');

        $newQty = $inventory->stock_quantity + $adjustment;

        if ($newQty < 0) {
            return response()->json([
                'message' => "Cannot reduce stock below zero. Current stock: {$inventory->stock_quantity}."
            ], 422);
        }

        $inventory->update(['stock_quantity' => $newQty]);

        return response()->json([
            'message' => 'Stock updated successfully.',
            'data' => [
                'id'             => $inventory->id,
                'product_name'   => $inventory->product->name,
                'branch_name'    => $inventory->branch->name,
                'stock_quantity' => $inventory->stock_quantity,
            ]
        ]);
    }
}
