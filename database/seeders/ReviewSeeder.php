<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        Review::create([
            'user_id' => 1, // Giả sử user_id 1 tồn tại
            'tour_id' => 1, // Giả sử tour_id 1 tồn tại
            'rating' => 5,
            'comment' => 'Tour tuyệt vời, hướng dẫn viên thân thiện!',
            'images' => null, // Không có ảnh mẫu
            'status' => 'active',
        ]);

        Review::create([
            'user_id' => 2,
            'tour_id' => 1,
            'rating' => 3,
            'comment' => 'Tour ổn, nhưng thời tiết không tốt.',
            'images' => null, // Path mẫu (tạo file giả nếu cần)
            'status' => 'pending', // Test status khác
        ]);
    }
}