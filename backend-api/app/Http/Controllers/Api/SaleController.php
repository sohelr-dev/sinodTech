<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\InsufficientStockException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Resources\SaleResource;
use App\Repositories\Contracts\SaleRepositoryInterface;
use App\Services\Sales\SaleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function __construct(
        private SaleService $saleService,
        private SaleRepositoryInterface $saleRepository,
    ) {}

    /**
     * List recent sales.
     */
    public function index(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 20);
        $sales = $this->saleRepository->getRecentSales($limit);

        return response()->json([
            'data' => SaleResource::collection($sales),
        ]);
    }

    /**
     * Create a new sale (checkout).
     * Delegates all business logic to SaleService.
     * Only catches the specific InsufficientStockException to return
     * a meaningful 422 response — any other exception is handled globally.
     */
    public function store(StoreSaleRequest $request): JsonResponse
    {
        try {
            $sale = $this->saleService->createSale($request->validated());

            return response()->json([
                'message' => 'Sale completed successfully.',
                'data'    => new SaleResource($sale->load('items.product', 'customer', 'branch', 'employee')),
            ], 201);

        } catch (InsufficientStockException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Show a specific sale with all details.
     */
    public function show(int $id): JsonResponse
    {
        $sale = $this->saleRepository->findById($id);

        return response()->json([
            'data' => new SaleResource($sale),
        ]);
    }
}
