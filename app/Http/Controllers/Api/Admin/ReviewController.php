<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    /**
     * Chú thích: Lấy danh sách TẤT CẢ đánh giá cho trang admin, có thể lọc theo status.
     */
    public function index(Request $request)
    {
        $query = Review::with(['user:id,name', 'tour:id,title']);

        // Chú thích: Cho phép lọc theo trạng thái, ví dụ: /api/admin/reviews?status=pending
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reviews = $query->latest()->paginate(15);

        return ReviewResource::collection($reviews);
    }

    /**
     * Chú thích: Cập nhật trạng thái của một đánh giá (Duyệt/Từ chối).
     */
    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['approved', 'rejected', 'pending'])]
        ]);

        $review->update($validated);

        return new ReviewResource($review);
    }

    /**
     * Chú thích: Xóa vĩnh viễn một đánh giá.
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return response()->noContent(); // Trả về 204 No Content là chuẩn cho API delete
    }
}