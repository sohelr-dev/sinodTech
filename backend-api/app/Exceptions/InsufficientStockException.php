<?php

namespace App\Exceptions;

use Exception;

/**
 * Thrown when a sale cannot be completed because the requested
 * quantity exceeds available stock in the inventory.
 * Using a dedicated exception (instead of a generic one) allows
 * the SaleController to return a precise 422 response with a clear
 * error message, rather than a generic 500 server error.
 */
class InsufficientStockException extends Exception
{
    public function __construct(string $productName, int $requested, int $available)
    {
        parent::__construct(
            "Insufficient stock for \"{$productName}\". Requested: {$requested}, Available: {$available}."
        );
    }
}
