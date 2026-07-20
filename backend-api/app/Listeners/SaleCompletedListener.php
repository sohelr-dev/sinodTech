<?php

namespace App\Listeners;

use App\Events\SaleCompleted;
use App\Services\Kpi\KpiService;
use App\Mail\SaleInvoiceMail;
use Illuminate\Support\Facades\Mail;


class SaleCompletedListener
{
    public function __construct(
        private KpiService $kpiService
    ) {}

    /**
     * Handle the event.
     */
    public function handle(SaleCompleted $event): void
    {
        // 1. Award KPI points to employee
        $this->kpiService->awardPointsForSale($event->sale);

        // 2. Send email invoice to customer if email is available
        $sale = $event->sale;
        if ($sale->customer && !empty($sale->customer->email)) {
            Mail::to($sale->customer->email)->queue(new SaleInvoiceMail($sale));
        }
    }
}
