<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Tour;
use App\Models\Booking; 
use App\Models\Review;  
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;   

class ReviewController extends Controller
{
    public function index(Tour $tour)
    {
        $reviews = $tour->publicReviews()
                         ->with('user:id,name')
                         ->paginate(10);

        return ReviewResource::collection($reviews);
    }

    public function store(StoreReviewRequest $request, Booking $booking)
    {
        $user = Auth::user();

        // 1. Kiểm tra Quyền sở hữu (Bảo mật)
        if ($booking->user_id !== $user->id) {
            return response()->json(['message' => 'Bạn không có quyền đánh giá đơn hàng này.'], 403); // 403 Forbidden
        }

        // 2. Kiểm tra Trạng thái Thanh toán (Yêu cầu 1)
        if ($booking->status !== 'paid') {
            return response()->json(['message' => 'Bạn chỉ có thể đánh giá các tour đã thanh toán.'], 403);
        }

        // 3. Kiểm tra Thời gian Hoàn thành (Yêu cầu 1)
        $booking->load(['tour', 'departure']);
        if (!$booking->tour || !$booking->departure) {
             return response()->json(['message' => 'Không tìm thấy dữ liệu tour hoặc ngày khởi hành.'], 400);
        }
        $ngay_khoi_hanh = Carbon::parse($booking->departure->departure_date);
        $so_ngay = $booking->tour->duration_days;
        $ngay_ket_thuc = $ngay_khoi_hanh->addDays($so_ngay - 1);
        
        if ($ngay_ket_thuc >= Carbon::today()) {
            return response()->json(['message' => 'Bạn chỉ có thể đánh giá sau khi tour đã kết thúc.'], 403);
        }

        // 4. Kiểm tra Tính duy nhất (Yêu cầu 1)
        $alreadyReviewed = Review::where('booking_id', $booking->id)->exists();
        if ($alreadyReviewed) {
            return response()->json(['message' => 'Bạn đã đánh giá đơn hàng này rồi.'], 409); // 409 Conflict
        }

        //  Tự động lọc nội dung
        $status = 'pending'; 
        $comment = $request->comment;
        
        $blacklist = config('blacklist.words', []); 

        foreach ($blacklist as $word) {
        
            if (stripos($comment, $word) !== false) {
                $status = 'hidden'; 
                break; 
            }
        }
  
        $review = Review::create([
            'user_id' => $user->id,
            'tour_id' => $booking->tour_id,
            'booking_id' => $booking->id,
            'parent_id' => null,
            'rating' => $request->rating,
            'comment' => $comment, 
            'status' => $status,    
        ]);

        $tour = $booking->tour; 

        if ($tour) {
            $stats = $tour->publicReviews() 
                ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as reviews_count')
                ->first();

            $tour->avg_rating    = round($stats->avg_rating ?? 0, 1); // VD: 3.5, 4.8...
            $tour->reviews_count = $stats->reviews_count ?? 0;
            $tour->save();
        }

        return response()->json([
            'message' => 'Cảm ơn bạn đã đánh giá! Đánh giá của bạn đang chờ duyệt.',
            'data' => new ReviewResource($review)
        ], 201); // 201 Created
    }
}