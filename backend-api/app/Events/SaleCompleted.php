<?php

namespace App\Events;

use App\Models\Sale;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Fired after a Sale is successfully created.
 * Listeners attached to this event handle cross-cutting concerns
 * such as KPI tracking and PDF invoice generation — keeping
 * SaleService focused purely on the sale transaction itself.
 */
class SaleCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(public Sale $sale) {}
}
