<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use App\Services\Customer\LostCustomerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function __construct(
        private LostCustomerService $lostCustomerService
    ) {}

    /**
     * Get dashboard stats.
     */
    public function stats(): JsonResponse
    {
        $today  = Carbon::today();
        $month  = Carbon::now()->startOfMonth();

        $totalSalesToday    = Sale::whereDate('created_at', $today)->sum('total_amount');
        $totalSalesMonth    = Sale::where('created_at', '>=', $month)->sum('total_amount');
        $salesTodayCount    = Sale::whereDate('created_at', $today)->count();

        $totalCustomers     = Customer::count();
        $lostCustomersCount = $this->lostCustomerService->findInactiveCustomers()->count();

        $totalProducts      = Product::count();
        $lowStockCount      = Inventory::where('stock_quantity', '<=', 5)->count();

        $topProduct = Sale::join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('sales.created_at', '>=', $month)
            ->selectRaw('products.name, SUM(sale_items.quantity) as total_sold')
            ->groupBy('products.name')
            ->orderByDesc('total_sold')
            ->first();

        $topEmployee = User::orderByDesc('kpi_score')->first();

        return response()->json([
            'data' => [
                'sales' => [
                    'today_revenue' => (float) $totalSalesToday,
                    'month_revenue' => (float) $totalSalesMonth,
                    'today_count'   => $salesTodayCount,
                ],
                'customers' => [
                    'total'  => $totalCustomers,
                    'lost'   => $lostCustomersCount,
                    'active' => $totalCustomers - $lostCustomersCount,
                ],
                'products' => [
                    'total'      => $totalProducts,
                    'low_stock'  => $lowStockCount,
                ],
                'top_product'  => $topProduct ? ['name' => $topProduct->name, 'sold' => $topProduct->total_sold] : null,
                'top_employee' => $topEmployee ? ['name' => $topEmployee->name, 'kpi_score' => $topEmployee->kpi_score] : null,
            ]
        ]);
    }
}
