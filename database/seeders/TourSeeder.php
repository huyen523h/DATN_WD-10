<?php

namespace Database\Seeders;

use App\Models\Tour;
use App\Models\Category;
use App\Models\TourImage;
use App\Models\TourSchedule;
use App\Models\TourDeparture;
use Illuminate\Database\Seeder;

class TourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->call(CategorySeeder::class);
            $categories = Category::all();
        }

        $tours = [
            [
                'title' => 'Đà Nẵng - Hội An 3N2Đ',
                'short_description' => 'Khám phá vẻ đẹp của Đà Nẵng và phố cổ Hội An',
                'description' => 'Tour du lịch Đà Nẵng - Hội An 3 ngày 2 đêm sẽ đưa bạn khám phá những điểm đến nổi tiếng nhất của miền Trung. Từ bãi biển Mỹ Khê tuyệt đẹp đến phố cổ Hội An lung linh đèn lồng, bạn sẽ có những trải nghiệm khó quên.',
                'price' => 2500000,
                'location' => 'Đà Nẵng, Hội An',
                'duration' => '3 ngày 2 đêm',
                'available_seats' => 50,
                'category_name' => 'Du lịch trong nước',
            ],
            [
                'title' => 'Phú Quốc 4N3Đ',
                'short_description' => 'Nghỉ dưỡng tại đảo ngọc Phú Quốc',
                'description' => 'Thư giãn tại bãi biển đẹp nhất Việt Nam với tour Phú Quốc 4 ngày 3 đêm. Bạn sẽ được tận hưởng không khí trong lành, thưởng thức hải sản tươi ngon và khám phá những bãi biển hoang sơ tuyệt đẹp.',
                'price' => 3200000,
                'location' => 'Phú Quốc, Kiên Giang',
                'duration' => '4 ngày 3 đêm',
                'available_seats' => 30,
                'category_name' => 'Du lịch nghỉ dưỡng',
            ],
            [
                'title' => 'Sapa - Fansipan 2N1Đ',
                'short_description' => 'Chinh phục nóc nhà Đông Dương',
                'description' => 'Tour Sapa 2 ngày 1 đêm đưa bạn khám phá vẻ đẹp hùng vĩ của núi rừng Tây Bắc. Chinh phục đỉnh Fansipan, tham quan các bản làng dân tộc và tận hưởng không khí mát mẻ của vùng cao.',
                'price' => 1800000,
                'location' => 'Sapa, Lào Cai',
                'duration' => '2 ngày 1 đêm',
                'available_seats' => 25,
                'category_name' => 'Du lịch khám phá',
            ],
            [
                'title' => 'Singapore 4N3Đ',
                'short_description' => 'Khám phá đảo quốc sư tử hiện đại',
                'description' => 'Tour Singapore 4 ngày 3 đêm đưa bạn khám phá đất nước hiện đại bậc nhất Đông Nam Á. Từ Marina Bay Sands đến Universal Studios, bạn sẽ có những trải nghiệm thú vị tại đảo quốc sư tử.',
                'price' => 8500000,
                'location' => 'Singapore',
                'duration' => '4 ngày 3 đêm',
                'available_seats' => 20,
                'category_name' => 'Du lịch nước ngoài',
            ],
            [
                'title' => 'Huế - Cố đô 2N1Đ',
                'short_description' => 'Tham quan cố đô Huế cổ kính',
                'description' => 'Tour Huế 2 ngày 1 đêm đưa bạn về với cố đô cổ kính của Việt Nam. Tham quan Đại Nội, lăng Khải Định, chùa Thiên Mụ và tận hưởng ẩm thực cung đình Huế.',
                'price' => 1200000,
                'location' => 'Huế, Thừa Thiên Huế',
                'duration' => '2 ngày 1 đêm',
                'available_seats' => 40,
                'category_name' => 'Du lịch văn hóa',
            ],
        ];

        foreach ($tours as $tourData) {
            $categoryName = $tourData['category_name'];
            unset($tourData['category_name']);

            $category = $categories->where('name', $categoryName)->first();
            if (!$category) {
                continue;
            }

            $tourData['category_id'] = $category->id;

            $tour = Tour::updateOrCreate(
                ['title' => $tourData['title']],
                $tourData
            );

            // Add sample image
            TourImage::updateOrCreate(
                ['tour_id' => $tour->id, 'is_main' => true],
                [
                    'tour_id' => $tour->id,
                    'image_url' => 'https://via.placeholder.com/800x600/007bff/ffffff?text=' . urlencode($tour->title),
                    'is_main' => true,
                    'alt_text' => $tour->title,
                    'sort_order' => 1,
                ]
            );

            // Add sample schedule
            TourSchedule::updateOrCreate(
                ['tour_id' => $tour->id, 'day' => 1],
                [
                    'tour_id' => $tour->id,
                    'day' => 1,
                    'title' => 'Khởi hành và tham quan',
                    'description' => 'Điểm khởi hành và các hoạt động trong ngày đầu tiên',
                    'start_time' => '08:00',
                    'end_time' => '18:00',
                    'location' => $tour->location,
                    'sort_order' => 1,
                ]
            );

            // Add sample departure
            TourDeparture::updateOrCreate(
                ['tour_id' => $tour->id, 'departure_date' => now()->addDays(30)],
                [
                    'tour_id' => $tour->id,
                    'departure_date' => now()->addDays(30),
                    'return_date' => now()->addDays(30 + (int) str_replace([' ngày', ' đêm'], '', $tour->duration)),
                    'available_seats' => $tour->available_seats,
                    'status' => 'active',
                    'notes' => 'Khởi hành từ sân bay',
                ]
            );
        }
    }
}
