<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Operation\StoreOperationServiceRequest;
use App\Http\Requests\Admin\Operation\UpdateOperationServiceRequest;
use App\Models\OperationService;
use App\Models\TourOperation;
use App\Services\TourOperationService as OperationServiceFacade;
use Illuminate\Http\JsonResponse;

class OperationServiceController extends Controller
{
    public function __construct(
        protected OperationServiceFacade $operationService
    ) {
    }

    public function store(StoreOperationServiceRequest $request, TourOperation $tourOperation): JsonResponse
    {
        $service = $this->operationService->addService($tourOperation, $request->validated());

        return response()->json($service, 201);
    }

    public function update(UpdateOperationServiceRequest $request, OperationService $operationService): JsonResponse
    {
        $operationService->update($request->validated());

        return response()->json($operationService->fresh());
    }

    public function destroy(OperationService $operationService): JsonResponse
    {
        $operationService->delete();

        return response()->json(['message' => 'Đã xoá dịch vụ vận hành']);
    }
}

