<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerAssignment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * CustomerAssignmentController
 */
class CustomerAssignmentController extends Controller
{
    /**
     * List all customer assignments.
     */
    public function index(): JsonResponse
    {
        $assignments = CustomerAssignment::with(['customer', 'employee'])
            ->latest('assigned_at')
            ->get()
            ->map(fn($item) => [
                'id'            => $item->id,
                'customer_id'   => $item->customer_id,
                'customer_name' => $item->customer->name ?? '—',
                'employee_id'   => $item->employee_id,
                'employee_name' => $item->employee->name ?? '—',
                'assigned_at'   => $item->assigned_at,
                'status'        => $item->status,
            ]);

        return response()->json(['data' => $assignments]);
    }

    /**
     * Assign a customer to an employee.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'employee_id' => ['required', 'exists:users,id'],
        ]);

        $assignment = CustomerAssignment::updateOrCreate(
            ['customer_id' => $request->customer_id],
            [
                'employee_id' => $request->employee_id,
                'assigned_at' => now(),
                'status'      => 'assigned',
            ]
        );

        return response()->json([
            'message' => 'Customer assigned successfully.',
            'data'    => $assignment->load(['customer', 'employee']),
        ], 201);
    }
}
