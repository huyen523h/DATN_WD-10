<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Tour;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Chú thích: Lấy danh sách các đánh giá đã được duyệt cho một tour.
     */
    public function index(Tour $tour)
    {
        $reviews = $tour->reviews()
                         ->where('status', 'approved') // Chỉ lấy các đánh giá có status là 'approved'
                         ->with('user:id,name')       // Lấy kèm thông tin cơ bản của user
                         ->latest()                   // Sắp xếp mới nhất lên đầu
                         ->paginate(10);

        return ReviewResource::collection($reviews);
    }

    /**
     * Chú thích: Lưu một đánh giá mới.
     */
    public function store(StoreReviewRequest $request, Tour $tour)
    {
        // Chú thích: Kiểm tra xem user đã đánh giá tour này chưa
        $alreadyReviewed = $tour->reviews()->where('user_id', Auth::id())->exists();
        if ($alreadyReviewed) {
            return response()->json(['message' => 'Bạn đã đánh giá tour này rồi.'], 409); // 409 Conflict
        }

        // Chú thích: Tạo đánh giá mới, status mặc định sẽ là 'pending' (nhờ migration)
        $review = $tour->reviews()->create([
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
            // 'images' => $request->images, // Nếu bạn có xử lý upload ảnh
        ]);

        return response()->json([
            'message' => 'Cảm ơn bạn đã đánh giá! Đánh giá của bạn đang chờ duyệt.',
            'data' => new ReviewResource($review)
        ], 201);
    }
}