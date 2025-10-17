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
            // 8 tour mẫu mới
            [
                'category_id' => $categories->where('name', 'Du lịch trong nước')->first()->id,
                'title' => 'Nha Trang - Đà Lạt 4N3Đ',
                'short_description' => 'Kết hợp biển xanh và cao nguyên mát mẻ',
                'description' => 'Tour Nha Trang - Đà Lạt 4 ngày 3 đêm khám phá thành phố biển Nha Trang với các hoạt động lặn biển, tham quan Vinpearl, sau đó lên Đà Lạt thưởng thức không khí mát mẻ và tham quan các điểm du lịch nổi tiếng.',
                'price' => 6500000,
                'location' => 'Nha Trang, Đà Lạt',
                'duration' => '4 ngày 3 đêm',
                'available_seats' => 28,
            ],
            [
                'category_id' => $categories->where('name', 'Du lịch biển đảo')->first()->id,
                'title' => 'Côn Đảo 3N2Đ',
                'short_description' => 'Hòn đảo lịch sử với thiên nhiên hoang sơ',
                'description' => 'Tour Côn Đảo 3 ngày 2 đêm khám phá hòn đảo lịch sử với bãi biển hoang sơ, tham quan nhà tù Côn Đảo, lặn biển ngắm san hô và thưởng thức hải sản tươi ngon.',
                'price' => 7200000,
                'location' => 'Côn Đảo, Bà Rịa - Vũng Tàu',
                'duration' => '3 ngày 2 đêm',
                'available_seats' => 22,
            ],
            [
                'category_id' => $categories->where('name', 'Du lịch trong nước')->first()->id,
                'title' => 'Hạ Long - Cát Bà 3N2Đ',
                'short_description' => 'Vịnh Hạ Long kỳ quan thiên nhiên thế giới',
                'description' => 'Tour Hạ Long - Cát Bà 3 ngày 2 đêm du thuyền trên vịnh Hạ Long, tham quan hang động, đảo Cát Bà với các hoạt động leo núi, tắm biển và khám phá rừng quốc gia.',
                'price' => 4800000,
                'location' => 'Hạ Long, Cát Bà, Quảng Ninh',
                'duration' => '3 ngày 2 đêm',
                'available_seats' => 35,
            ],
            [
                'category_id' => $categories->where('name', 'Du lịch nước ngoài')->first()->id,
                'title' => 'Singapore - Malaysia 5N4Đ',
                'short_description' => 'Khám phá hai quốc gia Đông Nam Á hiện đại',
                'description' => 'Tour Singapore - Malaysia 5 ngày 4 đêm tham quan Singapore với Marina Bay Sands, Gardens by the Bay, sau đó sang Malaysia tham quan Kuala Lumpur, Genting Highlands và Malacca.',
                'price' => 15800000,
                'location' => 'Singapore, Malaysia',
                'duration' => '5 ngày 4 đêm',
                'available_seats' => 18,
            ],
            [
                'category_id' => $categories->where('name', 'Du lịch trong nước')->first()->id,
                'title' => 'Huế - Quảng Bình 4N3Đ',
                'short_description' => 'Cố đô Huế và hang động kỳ vĩ',
                'description' => 'Tour Huế - Quảng Bình 4 ngày 3 đêm khám phá cố đô Huế với Đại Nội, lăng tẩm các vua, sau đó đến Quảng Bình tham quan hang Sơn Đoòng, Phong Nha - Kẻ Bàng.',
                'price' => 5800000,
                'location' => 'Huế, Quảng Bình',
                'duration' => '4 ngày 3 đêm',
                'available_seats' => 25,
            ],
            [
                'category_id' => $categories->where('name', 'Du lịch biển đảo')->first()->id,
                'title' => 'Lý Sơn 2N1Đ',
                'short_description' => 'Hòn đảo tỏi với cảnh đẹp thiên nhiên',
                'description' => 'Tour Lý Sơn 2 ngày 1 đêm khám phá hòn đảo tỏi nổi tiếng với bãi biển đẹp, tham quan chùa Hang, đảo Bé, thưởng thức hải sản tươi ngon và tìm hiểu văn hóa địa phương.',
                'price' => 2800000,
                'location' => 'Lý Sơn, Quảng Ngãi',
                'duration' => '2 ngày 1 đêm',
                'available_seats' => 30,
            ],
            [
                'category_id' => $categories->where('name', 'Du lịch nước ngoài')->first()->id,
                'title' => 'Nhật Bản - Tokyo 6N5Đ',
                'short_description' => 'Khám phá xứ sở mặt trời mọc',
                'description' => 'Tour Nhật Bản - Tokyo 6 ngày 5 đêm tham quan thủ đô Tokyo với các điểm nổi tiếng như Tokyo Skytree, chùa Senso-ji, khu phố Shibuya, thưởng thức ẩm thực Nhật Bản và mua sắm.',
                'price' => 28500000,
                'location' => 'Tokyo, Nhật Bản',
                'duration' => '6 ngày 5 đêm',
                'available_seats' => 12,
            ],
            [
                'category_id' => $categories->where('name', 'Du lịch trong nước')->first()->id,
                'title' => 'Mù Cang Chải 3N2Đ',
                'short_description' => 'Ruộng bậc thang vàng mùa lúa chín',
                'description' => 'Tour Mù Cang Chải 3 ngày 2 đêm ngắm ruộng bậc thang vàng rực mùa lúa chín, tham quan làng dân tộc Mông, chợ phiên địa phương và trải nghiệm văn hóa vùng cao Tây Bắc.',
                'price' => 4200000,
                'location' => 'Mù Cang Chải, Yên Bái',
                'duration' => '3 ngày 2 đêm',
                'available_seats' => 20,
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
