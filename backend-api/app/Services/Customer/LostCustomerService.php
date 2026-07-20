<?php

namespace App\Services\Customer;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class LostCustomerService
{
    /**
     * Find inactive ("lost") customers who have not made a purchase within the configurable period,
     * or who have never made a purchase.
     *
     * @param int|null $days
     * @return Collection<int, Customer>
     */
    public function findInactiveCustomers(?int $days = null): Collection
    {
        $days = $days ?? config('crm.lost_customer_days', 90);
        $thresholdDate = Carbon::now()->subDays($days);

        return Customer::where('last_purchase_at', '<', $thresholdDate)
            ->orWhereNull('last_purchase_at')
            ->get();
    }
}
