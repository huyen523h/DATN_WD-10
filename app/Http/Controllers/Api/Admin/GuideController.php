<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Guide\StoreGuideRequest;
use App\Http\Requests\Admin\Guide\UpdateGuideRequest;
use App\Models\Guide;
use App\Services\GuideService;
use Illuminate\Http\JsonResponse;

class GuideController extends Controller
{
    public function __construct(
        protected GuideService $guideService
    ) {
    }

    public function index(): JsonResponse
    {
        $guides = Guide::query()
            ->with(['categories', 'languages', 'documents', 'healthRecords'])
            ->when(request('keyword'), function ($query, $keyword) {
                $query->where('full_name', 'like', "%{$keyword}%")
                    ->orWhere('code', 'like', "%{$keyword}%")
                    ->orWhere('phone', 'like', "%{$keyword}%");
            })
            ->paginate(request('per_page', 15));

        return response()->json($guides);
    }

    public function show(Guide $guide): JsonResponse
    {
        $guide->load(['categories', 'languages', 'documents', 'healthRecords']);

        return response()->json($guide);
    }

    public function store(StoreGuideRequest $request): JsonResponse
    {
        $guide = $this->guideService->create($request->validated());

        return response()->json($guide, 201);
    }

    public function update(UpdateGuideRequest $request, Guide $guide): JsonResponse
    {
        $guide = $this->guideService->update($guide, $request->validated());

        return response()->json($guide);
    }

    public function destroy(Guide $guide): JsonResponse
    {
        $guide->delete();

        return response()->json([
            'message' => 'Đã xoá hướng dẫn viên',
        ]);
    }

}

