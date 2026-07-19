<?php

namespace App\Repositories\Contracts;

interface ProductRepositoryInterface
{
    public function getPaginatedProducts(int $perPage = 15);
    public function findBySku(string $sku);
    public function findById(int $id);
    public function getAvailableStock(int $productId, int $branchId);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}
