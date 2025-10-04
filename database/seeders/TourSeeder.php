<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tour;
use App\Models\Category;
use App\Models\TourImage;
use App\Models\TourSchedule;
use App\Models\TourDeparture;

class TourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        
        $tours = [
            [
                'category_id' => $categories->where('name', 'Du lịch trong nước')->first()->id,
                'title' => 'Đà Nẵng - Hội An 3N2Đ',
                'short_description' => 'Khám phá thành phố biển xinh đẹp và phố cổ Hội An',
                'description' => 'Tour du lịch Đà Nẵng - Hội An 3 ngày 2 đêm với lịch trình tham quan đầy đủ các điểm nổi tiếng như Bà Nà Hills, Cầu Vàng, phố cổ Hội An, chợ đêm Hội An...',
                'price' => 5500000,
                'location' => 'Đà Nẵng, Hội An',
                'duration' => '3 ngày 2 đêm',
                'available_seats' => 30,
            ],
            [
                'category_id' => $categories->where('name', 'Du lịch biển đảo')->first()->id,
                'title' => 'Phú Quốc 4N3Đ',
                'short_description' => 'Thiên đường biển đảo với bãi biển tuyệt đẹp',
                'description' => 'Tour Phú Quốc 4 ngày 3 đêm khám phá đảo ngọc với các hoạt động thú vị như lặn biển, câu cá, tham quan làng chài...',
                'price' => 7800000,
                'location' => 'Phú Quốc, Kiên Giang',
                'duration' => '4 ngày 3 đêm',
                'available_seats' => 25,
            ],
            [
                'category_id' => $categories->where('name', 'Du lịch trong nước')->first()->id,
                'title' => 'Sapa 2N1Đ',
                'short_description' => 'Khám phá vùng núi Tây Bắc với ruộng bậc thang',
                'description' => 'Tour Sapa 2 ngày 1 đêm tham quan ruộng bậc thang, làng dân tộc, chợ tình Sapa...',
                'price' => 3200000,
                'location' => 'Sapa, Lào Cai',
                'duration' => '2 ngày 1 đêm',
                'available_seats' => 20,
            ],
            [
                'category_id' => $categories->where('name', 'Du lịch nước ngoài')->first()->id,
                'title' => 'Thái Lan - Bangkok 5N4Đ',
                'short_description' => 'Khám phá xứ sở chùa vàng',
                'description' => 'Tour Thái Lan 5 ngày 4 đêm tham quan Bangkok, Pattaya với các điểm nổi tiếng như Cung điện Hoàng gia, chùa Wat Pho...',
                'price' => 12500000,
                'location' => 'Bangkok, Pattaya, Thái Lan',
                'duration' => '5 ngày 4 đêm',
                'available_seats' => 15,
            ],
        ];

        foreach ($tours as $tourData) {
            $tour = Tour::firstOrCreate(
                ['title' => $tourData['title']],
                $tourData
            );
            
            // Tạo hình ảnh mẫu nếu chưa có
            if (!$tour->images()->where('is_cover', true)->exists()) {
                TourImage::create([
                    'tour_id' => $tour->id,
                    'image_url' => 'https://via.placeholder.com/800x600/4F46E5/FFFFFF?text=' . urlencode($tour->title),
                    'is_cover' => true,
                    'sort_order' => 1,
                ]);
            }
            
            // Tạo lịch trình mẫu nếu chưa có
            if (!$tour->schedules()->exists()) {
                $schedules = [
                    [
                        'day_number' => 1,
                        'title' => 'Ngày 1: Khởi hành và tham quan',
                        'description' => 'Khởi hành từ TP.HCM, di chuyển đến điểm đến, check-in khách sạn và tham quan các điểm nổi tiếng.',
                    ],
                    [
                        'day_number' => 2,
                        'title' => 'Ngày 2: Tham quan chính',
                        'description' => 'Tham quan các điểm du lịch chính, thưởng thức ẩm thực địa phương.',
                    ],
                    [
                        'day_number' => 3,
                        'title' => 'Ngày 3: Hoàn thành tour',
                        'description' => 'Tham quan điểm cuối cùng, mua sắm và trở về TP.HCM.',
                    ],
                ];
                
                foreach ($schedules as $schedule) {
                    TourSchedule::create(array_merge($schedule, ['tour_id' => $tour->id]));
                }
            }
            
            // Tạo ngày khởi hành mẫu nếu chưa có
            if (!$tour->departures()->exists()) {
                for ($i = 0; $i < 3; $i++) {
                    TourDeparture::create([
                        'tour_id' => $tour->id,
                        'departure_date' => now()->addDays(7 + ($i * 7)),
                        'seats_total' => $tour->available_seats,
                        'seats_available' => $tour->available_seats,
                    ]);
                }
            }
        }
    }
}
