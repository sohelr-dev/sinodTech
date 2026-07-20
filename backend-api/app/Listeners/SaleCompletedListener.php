<?php

namespace App\Listeners;

use App\Events\SaleCompleted;
use App\Services\Kpi\KpiService;

/**
 * Listens for the SaleCompleted event and delegates to KpiService.
 *
 * Using an Event Listener here is the correct architectural choice:
 * - SaleService fires the event and does NOT know about KPI logic.
 * - This Listener handles the KPI concern in complete isolation.
 * - Adding more listeners (e.g. send invoice email) requires zero
 *   changes to SaleService — just register a new listener.
 * This pattern is called the "Open/Closed Principle".
 */
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
        $this->kpiService->awardPointsForSale($event->sale);
    }
}
