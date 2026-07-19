<?php

namespace App\Repositories\Eloquent;

use App\Models\Sale;
use App\Repositories\Contracts\SaleRepositoryInterface;

class SaleRepository implements SaleRepositoryInterface
{
    public function createSale(array $data)
    {
        return Sale::create($data);
    }

    public function getCustomerSalesHistory(int $customerId)
    {
        return Sale::with('items.product', 'branch')
            ->where('customer_id', $customerId)
            ->orderByDesc('created_at')
            ->get();
    }

    public function getRecentSales(int $limit = 10)
    {
        return Sale::with('customer', 'branch', 'employee')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function findById(int $id)
    {
        return Sale::with('items', 'customer', 'branch')->findOrFail($id);
    }
}
