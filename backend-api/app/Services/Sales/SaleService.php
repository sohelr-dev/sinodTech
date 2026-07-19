<?php

namespace App\Services\Sales;

use App\Events\SaleCompleted;
use App\Exceptions\InsufficientStockException;
use App\Models\Inventory;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * SaleService handles the complete checkout transaction.
 *
 * All database operations are wrapped in a single DB::transaction()
 * to guarantee atomicity — if any step fails (e.g. stock check),
 * all changes are rolled back automatically.
 *
 * lockForUpdate() is used on each Inventory row to prevent a race
 * condition known as "overselling": without it, two concurrent
 * requests could both read the same stock quantity, both pass the
 * check, and both deduct stock — resulting in negative inventory.
 * The lock forces concurrent requests to queue and process one at a time.
 */
class SaleService
{
    /**
     * Process a complete sale transaction.
     *
     * @param array{
     *   customer_id: int|null,
     *   branch_id: int,
     *   employee_id: int|null,
     *   items: array<array{product_id: int, quantity: int}>
     * } $data
     * @throws InsufficientStockException
     */
    public function createSale(array $data): Sale
    {
        return DB::transaction(function () use ($data) {
            $totalAmount = 0;
            $saleItemsData = [];

            foreach ($data['items'] as $item) {
                // Lock the inventory row for this product+branch combination.
                // This prevents concurrent requests from reading stale stock values.
                $inventory = Inventory::where('product_id', $item['product_id'])
                    ->where('branch_id', $data['branch_id'])
                    ->lockForUpdate()
                    ->first();

                $stockAvailable = $inventory ? $inventory->stock_quantity : 0;

                if ($stockAvailable < $item['quantity']) {
                    // Throw a specific exception so the controller can return
                    // a meaningful 422 response to the client.
                    throw new InsufficientStockException(
                        $inventory?->product->name ?? "Product #{$item['product_id']}",
                        $item['quantity'],
                        $stockAvailable
                    );
                }

                // Snapshot the current unit price into the sale item.
                // This ensures historical records remain accurate even
                // if the product price is changed in the future.
                $unitPrice = $inventory->product->price;
                $subtotal  = $unitPrice * $item['quantity'];
                $totalAmount += $subtotal;

                $saleItemsData[] = [
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'subtotal'   => $subtotal,
                ];

                // Deduct the sold quantity from inventory
                $inventory->decrement('stock_quantity', $item['quantity']);
            }

            // Create the Sale header record
            $sale = Sale::create([
                'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
                'customer_id'    => $data['customer_id'] ?? null,
                'branch_id'      => $data['branch_id'],
                'employee_id'    => $data['employee_id'] ?? null,
                'total_amount'   => $totalAmount,
                'status'         => 'completed',
            ]);

            // Create all sale line items
            $sale->items()->createMany($saleItemsData);

            // Update customer's last purchase timestamp for CRM lost-customer detection
            if ($sale->customer_id) {
                $sale->customer->update(['last_purchase_at' => now()]);
            }

            // Dispatch event so listeners can handle KPI tracking, invoice emails, etc.
            SaleCompleted::dispatch($sale->load('items.product', 'customer', 'branch'));

            return $sale;
        });
    }
}
