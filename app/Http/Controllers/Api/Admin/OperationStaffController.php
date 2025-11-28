<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Operation\AssignStaffRequest;
use App\Models\OperationStaffAssignment;
use App\Models\TourOperation;
use App\Services\TourOperationService;
use Illuminate\Http\JsonResponse;

class OperationStaffController extends Controller
{
    public function __construct(
        protected TourOperationService $operationService
    ) {
    }

    public function store(AssignStaffRequest $request, TourOperation $tourOperation): JsonResponse
    {
        $assignment = $this->operationService->assignStaff($tourOperation, $request->validated());

        return response()->json($assignment, 201);
    }

    public function update(AssignStaffRequest $request, OperationStaffAssignment $assignment): JsonResponse
    {
        $assignment->update($request->validated());

        return response()->json($assignment->fresh(['guide', 'user']));
    }

    public function destroy(OperationStaffAssignment $assignment): JsonResponse
    {
        $assignment->delete();

        return response()->json(['message' => 'Đã xoá phân công nhân sự']);
    }
}

