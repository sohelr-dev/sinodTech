<?php

namespace App\Repositories\Contracts;

interface SaleRepositoryInterface
{
    public function createSale(array $data);
    public function getCustomerSalesHistory(int $customerId);
    public function getRecentSales(int $limit = 10);
    public function findById(int $id);
}
