<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Services\Customer\LostCustomerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct(
        private LostCustomerService $lostCustomerService
    ) {}

    /**
     * Display a listing of the customers, optionally filtered by lost status.
     */
    public function index(Request $request): JsonResponse
    {
        $filterLost = $request->boolean('lost');

        if ($filterLost) {
            // Retrieve inactive customers, loading sales aggregates manually/using query
            $customers = $this->lostCustomerService->findInactiveCustomers();
            $customers->loadCount('sales')->loadSum('sales', 'total_amount');
        } else {
            $customers = Customer::withCount('sales')
                ->withSum('sales', 'total_amount')
                ->get();
        }

        return response()->json([
            'data' => CustomerResource::collection($customers)
        ]);
    }

    /**
     * Display the customer details, including full purchase history (sales & items).
     */
    public function show(int $id): JsonResponse
    {
        $customer = Customer::withCount('sales')
            ->withSum('sales', 'total_amount')
            ->with(['sales' => function ($query) {
                $query->orderByDesc('created_at');
            }, 'sales.items.product', 'sales.branch', 'sales.employee'])
            ->findOrFail($id);

        return response()->json([
            'data' => new CustomerResource($customer)
        ]);
    }
}
