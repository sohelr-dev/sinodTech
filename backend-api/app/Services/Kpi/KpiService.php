<?php

namespace App\Services\Kpi;

use App\Models\CustomerAssignment;
use App\Models\Sale;
use App\Services\Customer\LostCustomerService;


class KpiService
{
    public function __construct(
        private LostCustomerService $lostCustomerService
    ) {}


    public function awardPointsForSale(Sale $sale): void
    {
        // If no employee processed this sale, there's nothing to award
        if (!$sale->employee_id) {
            return;
        }
        if (!$sale->customer_id || !$sale->customer) {
            return;
        }

        // Check if this customer was "lost" before this sale recovered them.
        // We check the customer's original last_purchase_at by looking at their
        // *previous* sale (not counting the current one just created).
        $previousSaleCount = $sale->customer->sales()
            ->where('id', '!=', $sale->id)
            ->where('status', 'completed')
            ->count();

        // If the customer had no previous sales, they were "never purchased"
        // which is also treated as a lost/inactive customer — award points.
        // If they had previous sales, check if they were inactive before this sale.
        $wasLost = false;

        if ($previousSaleCount === 0) {
            // First-ever sale for this customer — treat as recovering a lost/new lead
            $wasLost = true;
        } else {
            // Find the most recent previous sale date
            $lastSaleBeforeCurrent = $sale->customer->sales()
                ->where('id', '!=', $sale->id)
                ->where('status', 'completed')
                ->orderByDesc('created_at')
                ->first();

            if ($lastSaleBeforeCurrent) {
                $daysSinceLastPurchase = $lastSaleBeforeCurrent->created_at
                    ->diffInDays(now());
                $threshold = config('crm.lost_customer_days', 90);
                $wasLost = $daysSinceLastPurchase >= $threshold;
            }
        }

        if ($wasLost) {
            $sale->employee()->increment('kpi_score');

            // Mark active assignments for this customer as completed
            CustomerAssignment::where('customer_id', $sale->customer_id)
                ->where('status', 'assigned')
                ->update(['status' => 'completed']);
        }
    }
}
