<?php

namespace App\Observers;

use App\Models\Review;
use App\Models\Tour; // <-- THÊM MỚI: Import model Tour

class ReviewObserver
{
    public function created(Review $review): void
    {
   
    }

    public function updated(Review $review): void
    {

        if ($review->wasChanged('status') && is_null($review->parent_id)) {
            $this->updateTourRating($review->tour_id);
        }
    }


    public function deleted(Review $review): void
    {
        // Chỉ tính toán lại nếu đây là review gốc
        if (is_null($review->parent_id)) {
            $this->updateTourRating($review->tour_id);
        }
    }

    public function restored(Review $review): void
    {
        // Chỉ tính toán lại nếu đây là review gốc
        if (is_null($review->parent_id)) {
            $this->updateTourRating($review->tour_id);
        }
    }


    public function forceDeleted(Review $review): void
    {
        // Chỉ tính toán lại nếu đây là review gốc
        if (is_null($review->parent_id)) {
            $this->updateTourRating($review->tour_id);
        }
    }


    protected function updateTourRating($tourId): void
    {
        if (!$tourId) {
            return;
        }
        
        $tour = Tour::find($tourId);

        if ($tour) {
            
  
            $query = $tour->reviews()
                          ->where('status', 'approved') 
                          ->whereNull('parent_id');

            // 2. Tính toán
            $count = $query->count();
            $avg = $query->avg('rating');

            // 3. Cập nhật bảng 'tours'
            $tour->update([
                'avg_rating' => $avg ?? 0.00, 
                'reviews_count' => $count
            ]);
        }
    }
}