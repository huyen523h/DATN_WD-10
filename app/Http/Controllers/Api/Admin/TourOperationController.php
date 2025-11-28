<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Operation\StoreTourOperationRequest;
use App\Http\Requests\Admin\Operation\UpdateTourOperationRequest;
use App\Models\TourOperation;
use App\Services\TourOperationService;
use Illuminate\Http\JsonResponse;

class TourOperationController extends Controller
{
    public function __construct(
        protected TourOperationService $operationService
    ) {
    }

    public function index(): JsonResponse
    {
        $operations = TourOperation::with(['tour', 'departure', 'staffAssignments', 'services'])
            ->when(request('status'), fn ($q, $status) => $q->where('status', $status))
            ->when(request('tour_id'), fn ($q, $tourId) => $q->where('tour_id', $tourId))
            ->latest()
            ->paginate(request('per_page', 15));

        return response()->json($operations);
    }

    public function show(TourOperation $tourOperation): JsonResponse
    {
        $tourOperation->load(['tour', 'departure', 'staffAssignments.guide', 'staffAssignments.user', 'services']);

        return response()->json($tourOperation);
    }

    public function store(StoreTourOperationRequest $request): JsonResponse
    {
        $operation = $this->operationService->create($request->validated());

        return response()->json($operation, 201);
    }

    public function update(UpdateTourOperationRequest $request, TourOperation $tourOperation): JsonResponse
    {
        $operation = $this->operationService->update($tourOperation, $request->validated());

        return response()->json($operation);
    }

    public function destroy(TourOperation $tourOperation): JsonResponse
    {
        $tourOperation->delete();

        return response()->json([
            'message' => 'Đã xoá lịch vận hành tour',
        ]);
    }
}

