<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'tour'])->where('status', 'active'); // Chỉ lấy active reviews

        if ($request->has('tour_id')) {
            $query->where('tour_id', $request->tour_id);
        }

        $reviews = $query->paginate(10); // Phân trang

        return response()->json([
            'success' => true,
            'data' => $reviews,
        ], 200);
    }

    public function store(StoreReviewRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id(); // Lấy user hiện tại
        $data['status'] = $data['status'] ?? 'active'; // Mặc định active nếu không set

        // Check duplicate: User đã review tour này chưa?
        if (Review::where('user_id', $data['user_id'])->where('tour_id', $data['tour_id'])->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã đánh giá tour này rồi!',
            ], 422);
        }

        // Xử lý upload images
        if ($request->hasFile('images')) {
            $data['images'] = $request->file('images')->store('images/reviews', 'public');
        }

        $review = Review::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Đánh giá đã được tạo!',
            'data' => $review->load(['user', 'tour']),
        ], 201);
    }

    public function show(Review $review)
    {
        // Check ownership hoặc admin
        if ($review->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Không có quyền xem đánh giá này!',
            ], 403);
        }

        $review->load(['user', 'tour']);

        return response()->json([
            'success' => true,
            'data' => $review,
        ], 200);
    }

    // chưa chạy được update API 
    public function update(UpdateReviewRequest $request, Review $review)
    {
        // Check ownership hoặc admin
        if ($review->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền chỉnh sửa đánh giá này!',
            ], 403);
        }

        $data = $request->all(); // Lấy tất cả input (validated đã filter)

        // Xử lý update images (xóa cũ nếu có mới)
        if ($request->hasFile('images')) {
            // Xóa file cũ nếu tồn tại
            if ($review->images) {
                Storage::disk('public')->delete($review->images);
            }
            $data['images'] = $request->file('images')->store('images/reviews', 'public');
        }

        // Update chỉ field có giá trị (bỏ qua empty)
        $review->update($data, ['timestamps' => false]); // Skip timestamps nếu có

        return response()->json([
            'success' => true,
            'message' => 'Đánh giá đã được cập nhật!',
            'data' => $review->load(['user', 'tour']),
        ], 200);
    }

    public function destroy(Review $review)
    {
        // Check ownership hoặc admin
        if ($review->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền xóa đánh giá này!',
            ], 403);
        }

        // Xóa file images nếu có
        if ($review->images) {
            Storage::disk('public')->delete($review->images);
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đánh giá đã được xóa!',
        ], 200);
    }
}
