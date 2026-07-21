<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EcommerceApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_fetch_ecommerce_products_list(): void
    {
        $user = User::factory()->create();
        $branch = Branch::create(['name' => 'Main Branch', 'location' => 'Dhaka']);

        $product = Product::create([
            'name' => 'Wireless Mouse',
            'sku'  => 'WM-001',
            'price' => 25.50,
            'description' => 'Ergonomic wireless mouse',
        ]);

        Inventory::create([
            'product_id' => $product->id,
            'branch_id'  => $branch->id,
            'stock_quantity' => 50,
        ]);

        $response = $this->actingAs($user)
            ->getJson('/api/v1/ecommerce/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => [
                        'id',
                        'sku',
                        'product_name',
                        'price',
                        'available_stock',
                        'description',
                        'updated_at',
                    ]
                ],
                'meta' => ['current_page', 'last_page', 'per_page', 'total']
            ])
            ->assertJsonFragment([
                'sku' => 'WM-001',
                'product_name' => 'Wireless Mouse',
                'price' => 25.50,
                'available_stock' => 50,
            ]);
    }
}
