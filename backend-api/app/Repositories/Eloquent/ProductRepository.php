<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Models\Inventory;
use App\Repositories\Contracts\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function getPaginatedProducts(int $perPage = 15)
    {
        return Product::paginate($perPage);
    }

    public function findBySku(string $sku)
    {
        return Product::where('sku', $sku)->first();
    }

    public function findById(int $id)
    {
        return Product::findOrFail($id);
    }

    public function getAvailableStock(int $productId, int $branchId)
    {
        $inventory = Inventory::where('product_id', $productId)
            ->where('branch_id', $branchId)
            ->first();

        return $inventory ? $inventory->stock_quantity : 0;
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update(int $id, array $data)
    {
        $product = $this->findById($id);
        $product->update($data);
        return $product;
    }

    public function delete(int $id)
    {
        $product = $this->findById($id);
        return $product->delete();
    }
}
