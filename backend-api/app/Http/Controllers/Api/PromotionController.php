<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\PromotionLog;
use App\Services\Customer\LostCustomerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * PromotionController
 */
class PromotionController extends Controller
{
    public function __construct(
        private LostCustomerService $lostCustomerService
    ) {}

    /**
     * Send promotions to all inactive customers.
     */
    public function sendToLost(Request $request): JsonResponse
    {
        $channel  = $request->input('channel', 'email');
        $today    = now()->toDateString();
        $lostCustomers = $this->lostCustomerService->findInactiveCustomers();

        $sent    = 0;
        $skipped = 0;

        foreach ($lostCustomers as $customer) {
            $alreadySent = PromotionLog::where('customer_id', $customer->id)
                ->where('channel', $channel)
                ->whereDate('sent_at', $today)
                ->exists();

            if ($alreadySent) {
                $skipped++;
                continue;
            }

            PromotionLog::create([
                'customer_id' => $customer->id,
                'channel'     => $channel,
                'sent_at'     => now(),
                'status'      => 'sent',
            ]);

            $sent++;
        }

        return response()->json([
            'message' => "Promotions sent to {$sent} customer(s). Skipped {$skipped} (already contacted today).",
            'sent'    => $sent,
            'skipped' => $skipped,
        ]);
    }

    /**
     * Get promotion history log.
     */
    public function index(): JsonResponse
    {
        $logs = PromotionLog::with('customer')
            ->latest('sent_at')
            ->limit(100)
            ->get()
            ->map(fn($log) => [
                'id'            => $log->id,
                'customer_name' => $log->customer->name ?? '—',
                'channel'       => $log->channel,
                'status'        => $log->status,
                'sent_at'       => $log->sent_at->toDateTimeString(),
            ]);

        return response()->json(['data' => $logs]);
    }
}
