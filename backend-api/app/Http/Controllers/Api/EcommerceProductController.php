<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EcommerceProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class EcommerceProductController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $query = Product::query()
            ->withSum('inventories', 'stock_quantity')
            ->with('inventories');

        if ($request->filled('sku')) {
            $query->where('sku', $request->query('sku'));
        }

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->get('per_page', 20);
        $products = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data'   => EcommerceProductResource::collection($products),
            'meta'   => [
                'current_page' => $products->currentPage(),
                'last_page'    => $products->lastPage(),
                'per_page'     => $products->perPage(),
                'total'        => $products->total(),
            ],
        ]);
    }

    public function show(string $identifier): JsonResponse
    {
        $product = Product::withSum('inventories', 'stock_quantity')
            ->with('inventories')
            ->where('id', $identifier)
            ->orWhere('sku', $identifier)
            ->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data'   => new EcommerceProductResource($product),
        ]);
    }
}
