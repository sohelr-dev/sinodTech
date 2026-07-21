<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Laptop HP ProBook', 'sku' => 'HP-PB-450', 'price' => 75000.00, 'description' => 'HP ProBook 450 G10'],
            ['name' => 'MacBook Air M2', 'sku' => 'MAC-AIR-M2', 'price' => 125000.00, 'description' => 'Apple MacBook Air M2 8GB/256GB'],
            ['name' => 'iPhone 15 Pro', 'sku' => 'IPHONE-15P', 'price' => 135000.00, 'description' => 'Apple iPhone 15 Pro 128GB'],
            ['name' => 'Samsung Galaxy S24', 'sku' => 'SAMS-S24', 'price' => 95000.00, 'description' => 'Samsung Galaxy S24 Ultra'],
            ['name' => 'Asus ROG Ally', 'sku' => 'ASUS-ROG-ALLY', 'price' => 68000.00, 'description' => 'Asus ROG Ally Handheld Console'],
        ];

        foreach ($products as $p) {
            Product::firstOrCreate(['sku' => $p['sku']], $p);
        }
    }
}
