<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    /**
     * Display a listing of banners.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Banner::query();

        // Filter by type
        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        // Filter by position
        if ($request->filled('position')) {
            $query->byPosition($request->position);
        }

        // Filter by active status
        if ($request->filled('active')) {
            if ($request->boolean('active')) {
                $query->currentlyActive();
            } else {
                $query->where('is_active', false);
            }
        }

        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Order by sort_order and created_at
        $query->ordered();

        // Pagination
        $perPage = $request->get('per_page', 15);
        $banners = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $banners->items(),
            'pagination' => [
                'current_page' => $banners->currentPage(),
                'last_page' => $banners->lastPage(),
                'per_page' => $banners->perPage(),
                'total' => $banners->total(),
                'from' => $banners->firstItem(),
                'to' => $banners->lastItem(),
            ]
        ]);
    }

    /**
     * Store a newly created banner.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'image_url' => 'required|url|max:500',
            'link_url' => 'nullable|url|max:500',
            'type' => 'required|in:hero,promotion,category,featured',
            'position' => 'required|in:top,middle,bottom,sidebar',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'target_audience' => 'nullable|array',
            'target_audience.*' => 'in:all,new_users,returning_users',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $banner = Banner::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Banner created successfully',
            'data' => $banner->load([])
        ], 201);
    }

    /**
     * Display the specified banner.
     */
    public function show(Banner $banner): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $banner
        ]);
    }

    /**
     * Update the specified banner.
     */
    public function update(Request $request, Banner $banner): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:200',
            'description' => 'nullable|string',
            'image_url' => 'sometimes|required|url|max:500',
            'link_url' => 'nullable|url|max:500',
            'type' => 'sometimes|required|in:hero,promotion,category,featured',
            'position' => 'sometimes|required|in:top,middle,bottom,sidebar',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'target_audience' => 'nullable|array',
            'target_audience.*' => 'in:all,new_users,returning_users',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $banner->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Banner updated successfully',
            'data' => $banner->fresh()
        ]);
    }

    /**
     * Remove the specified banner.
     */
    public function destroy(Banner $banner): JsonResponse
    {
        $banner->delete();

        return response()->json([
            'success' => true,
            'message' => 'Banner deleted successfully'
        ]);
    }

    /**
     * Get active banners for public display.
     */
    public function getActive(Request $request): JsonResponse
    {
        $query = Banner::currentlyActive();

        // Filter by type
        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        // Filter by position
        if ($request->filled('position')) {
            $query->byPosition($request->position);
        }

        $banners = $query->ordered()->get();

        return response()->json([
            'success' => true,
            'data' => $banners
        ]);
    }

    /**
     * Increment view count for a banner.
     */
    public function trackView(Banner $banner): JsonResponse
    {
        $banner->incrementViewCount();

        return response()->json([
            'success' => true,
            'message' => 'View tracked successfully',
            'data' => [
                'id' => $banner->id,
                'view_count' => $banner->fresh()->view_count
            ]
        ]);
    }

    /**
     * Increment click count for a banner.
     */
    public function trackClick(Banner $banner): JsonResponse
    {
        $banner->incrementClickCount();

        return response()->json([
            'success' => true,
            'message' => 'Click tracked successfully',
            'data' => [
                'id' => $banner->id,
                'click_count' => $banner->fresh()->click_count
            ]
        ]);
    }

    /**
     * Get banner statistics.
     */
    public function getStats(): JsonResponse
    {
        $stats = [
            'total_banners' => Banner::count(),
            'active_banners' => Banner::currentlyActive()->count(),
            'inactive_banners' => Banner::where('is_active', false)->count(),
            'total_views' => Banner::sum('view_count'),
            'total_clicks' => Banner::sum('click_count'),
            'by_type' => Banner::selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->get()
                ->pluck('count', 'type'),
            'by_position' => Banner::selectRaw('position, COUNT(*) as count')
                ->groupBy('position')
                ->get()
                ->pluck('count', 'position'),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Bulk update banner status.
     */
    public function bulkUpdateStatus(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'banner_ids' => 'required|array',
            'banner_ids.*' => 'exists:banners,id',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $updated = Banner::whereIn('id', $request->banner_ids)
            ->update(['is_active' => $request->is_active]);

        return response()->json([
            'success' => true,
            'message' => "Updated {$updated} banners successfully",
            'data' => ['updated_count' => $updated]
        ]);
    }

    /**
     * Reorder banners.
     */
    public function reorder(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'banners' => 'required|array',
            'banners.*.id' => 'required|exists:banners,id',
            'banners.*.sort_order' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        foreach ($request->banners as $bannerData) {
            Banner::where('id', $bannerData['id'])
                ->update(['sort_order' => $bannerData['sort_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Banners reordered successfully'
        ]);
    }
}
