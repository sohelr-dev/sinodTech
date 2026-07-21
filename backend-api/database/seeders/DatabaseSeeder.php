<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserRoleSeeder::class,
            BranchSeeder::class,
            ProductSeeder::class,
            CustomerSeeder::class,
            SaleSeeder::class,
        ]);

        // Seed inventory for all products across all branches
        $branches = \App\Models\Branch::all();
        $products = \App\Models\Product::all();

        foreach ($branches as $branch) {
            foreach ($products as $product) {
                \App\Models\Inventory::firstOrCreate([
                    'product_id' => $product->id,
                    'branch_id' => $branch->id,
                ], [
                    'stock_quantity' => rand(20, 100)
                ]);
            }
        }
    }
}
