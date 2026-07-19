<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => Customer::all()
        ]);
    }
}
