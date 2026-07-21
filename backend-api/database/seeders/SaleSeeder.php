<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\CustomerAssignment;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $branches  = Branch::all();
        $products  = Product::all();
        $customers = Customer::all();
        $employees = User::all();

        if ($branches->isEmpty() || $products->isEmpty() || $customers->isEmpty() || $employees->isEmpty()) {
            return;
        }

        // 1. Create historical sales for active customers (within last 30 days)
        $activeCustomers = $customers->filter(fn($c) => $c->last_purchase_at && $c->last_purchase_at->diffInDays(now()) < 90);

        foreach ($activeCustomers as $customer) {
            // Create 2 to 4 sales per active customer
            for ($i = 0; $i < rand(2, 4); $i++) {
                $branch   = $branches->random();
                $employee = $employees->random();
                $createdDate = now()->subDays(rand(1, 40));

                $sale = Sale::create([
                    'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
                    'customer_id'    => $customer->id,
                    'branch_id'      => $branch->id,
                    'employee_id'    => $employee->id,
                    'total_amount'   => 0,
                    'status'         => 'completed',
                    'created_at'     => $createdDate,
                    'updated_at'     => $createdDate,
                ]);

                $totalAmount = 0;
                $randomProducts = $products->random(rand(1, 3));

                foreach ($randomProducts as $product) {
                    $qty = rand(1, 3);
                    $subtotal = $product->price * $qty;
                    $totalAmount += $subtotal;

                    $sale->items()->create([
                        'product_id' => $product->id,
                        'quantity'   => $qty,
                        'unit_price' => $product->price,
                        'subtotal'   => $subtotal,
                        'created_at' => $createdDate,
                        'updated_at' => $createdDate,
                    ]);
                }

                $sale->update(['total_amount' => $totalAmount]);
            }
        }

        // 2. Create old sales for lost/inactive customers (more than 90 days ago)
        $lostCustomers = $customers->filter(fn($c) => !$c->last_purchase_at || $c->last_purchase_at->diffInDays(now()) >= 90);

        foreach ($lostCustomers as $customer) {
            if (!$customer->last_purchase_at) {
                continue;
            }

            $branch      = $branches->random();
            $employee    = $employees->random();
            $createdDate = $customer->last_purchase_at;

            $sale = Sale::create([
                'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
                'customer_id'    => $customer->id,
                'branch_id'      => $branch->id,
                'employee_id'    => $employee->id,
                'total_amount'   => 0,
                'status'         => 'completed',
                'created_at'     => $createdDate,
                'updated_at'     => $createdDate,
            ]);

            $totalAmount = 0;
            $randomProducts = $products->random(rand(1, 2));

            foreach ($randomProducts as $product) {
                $qty = rand(1, 2);
                $subtotal = $product->price * $qty;
                $totalAmount += $subtotal;

                $sale->items()->create([
                    'product_id' => $product->id,
                    'quantity'   => $qty,
                    'unit_price' => $product->price,
                    'subtotal'   => $subtotal,
                    'created_at' => $createdDate,
                    'updated_at' => $createdDate,
                ]);
            }

            $sale->update(['total_amount' => $totalAmount]);

            // Seed an active CRM assignment for follow-up by an employee
            CustomerAssignment::create([
                'customer_id' => $customer->id,
                'employee_id' => $employee->id,
                'assigned_at' => now()->subDays(5),
                'status'      => 'assigned',
            ]);
        }
    }
}
