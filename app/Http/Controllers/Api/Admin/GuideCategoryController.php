<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Guide\StoreGuideCategoryRequest;
use App\Models\GuideCategory;
use Illuminate\Http\JsonResponse;

class GuideCategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = GuideCategory::query()
            ->when(request('type'), fn ($q, $type) => $q->where('type', $type))
            ->orderBy('name')
            ->get();

        return response()->json($categories);
    }

    public function store(StoreGuideCategoryRequest $request): JsonResponse
    {
        $category = GuideCategory::create($request->validated());

        return response()->json($category, 201);
    }

    public function show(GuideCategory $guideCategory): JsonResponse
    {
        return response()->json($guideCategory);
    }

    public function update(StoreGuideCategoryRequest $request, GuideCategory $guideCategory): JsonResponse
    {
        $guideCategory->update($request->validated());

        return response()->json($guideCategory);
    }

    public function destroy(GuideCategory $guideCategory): JsonResponse
    {
        if ($guideCategory->guides()->exists()) {
            return response()->json([
                'message' => 'Không thể xoá vì đang được sử dụng.',
            ], 422);
        }

        $guideCategory->delete();

        return response()->json([
            'message' => 'Đã xoá nhóm hướng dẫn viên',
        ]);
    }
}

