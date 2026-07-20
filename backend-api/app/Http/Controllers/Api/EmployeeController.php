<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{
    /**
     * Return all employees (non-admin) sorted by KPI score — leaderboard style.
     */
    public function index(): JsonResponse
    {
        $employees = User::where('role', '!=', 'admin')
            ->withCount('salesProcessed')
            ->withSum('salesProcessed', 'total_amount')
            ->orderByDesc('kpi_score')
            ->get()
            ->map(fn($user) => [
                'id'                    => $user->id,
                'name'                  => $user->name,
                'email'                 => $user->email,
                'role'                  => $user->role,
                'kpi_score'             => $user->kpi_score,
                'total_sales'           => $user->sales_processed_count ?? 0,
                'total_revenue'         => (float) ($user->sales_processed_sum_total_amount ?? 0),
            ]);

        return response()->json(['data' => $employees]);
    }
}
