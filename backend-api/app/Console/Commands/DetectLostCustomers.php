<?php

namespace App\Console\Commands;

use App\Services\Customer\LostCustomerService;
use Illuminate\Console\Command;

class DetectLostCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crm:detect-lost-customers {--days= : Override the default inactivity days threshold}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Detect inactive customers who have not made any purchases within the configured period.';

    /**
     * Execute the console command.
     */
    public function handle(LostCustomerService $lostCustomerService): int
    {
        $daysInput = $this->option('days');
        $days = $daysInput ? (int) $daysInput : null;
        $threshold = $days ?? config('crm.lost_customer_days', 90);

        $this->info("Scanning database for customers with no purchases in the last {$threshold} days...");

        $inactiveCustomers = $lostCustomerService->findInactiveCustomers($days);
        $count = $inactiveCustomers->count();

        $this->info("Scan completed.");
        if ($count > 0) {
            $this->warn("Found {$count} inactive customer(s):");
            $headers = ['ID', 'Name', 'Email', 'Last Purchase At'];
            $data = $inactiveCustomers->map(fn($c) => [
                $c->id,
                $c->name,
                $c->email ?? 'N/A',
                $c->last_purchase_at ? $c->last_purchase_at->toDateTimeString() : 'Never'
            ])->toArray();
            
            $this->table($headers, $data);
        } else {
            $this->info("No inactive customers found.");
        }

        return self::SUCCESS;
    }
}
