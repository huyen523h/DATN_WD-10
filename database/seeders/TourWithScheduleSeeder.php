<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tour;
use App\Models\Category;
use App\Models\TourImage;
use App\Models\TourSchedule;
use App\Models\TourDeparture;
use Carbon\Carbon;

class TourWithScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        
        // Đảm bảo các category tồn tại
        $categoryTrongNuoc = $categories->where('name', 'Du lịch trong nước')->first();
        $categoryBienDao = $categories->where('name', 'Du lịch biển đảo')->first();
        
        if (!$categoryTrongNuoc) {
            $categoryTrongNuoc = Category::firstOrCreate(
                ['name' => 'Du lịch trong nước'],
                ['description' => 'Các tour du lịch trong nước Việt Nam']
            );
        }
        
        if (!$categoryBienDao) {
            $categoryBienDao = Category::firstOrCreate(
                ['name' => 'Du lịch biển đảo'],
                ['description' => 'Các tour du lịch biển và đảo']
            );
        }
        
        // Tour 1: Đà Nẵng - Hội An 3N2Đ
        $tour1 = $this->createTour([
            'category_id' => $categoryTrongNuoc->id,
            'title' => 'Đà Nẵng - Hội An 3N2Đ',
            'short_description' => 'Khám phá thành phố biển xinh đẹp và phố cổ Hội An',
            'description' => 'Tour du lịch Đà Nẵng - Hội An 3 ngày 2 đêm với lịch trình tham quan đầy đủ các điểm nổi tiếng như Bà Nà Hills, Cầu Vàng, phố cổ Hội An, chợ đêm Hội An...',
            'price' => 5500000,
            'price_adult' => 5500000,
            'price_child' => 4500000,
            'location' => 'Đà Nẵng, Hội An',
            'duration' => '3 ngày 2 đêm',
            'duration_days' => 3,
            'duration_nights' => 2,
            'available_seats' => 30,
            'status' => 'active',
        ], [
            [
                'day_number' => 1,
                'title' => 'Ngày 1: Khởi hành - Đà Nẵng - Bà Nà Hills',
                'description' => 'Khởi hành từ TP.HCM, di chuyển đến Đà Nẵng, tham quan Bà Nà Hills và Cầu Vàng',
                'location' => 'Đà Nẵng',
                'start_time' => '05:00',
                'end_time' => '20:00',
                'meeting_point' => 'Văn phòng công ty - 123 Nguyễn Huệ, Q1, TP.HCM',
                'activities' => 'Di chuyển bằng máy bay, tham quan Bà Nà Hills, đi cáp treo, chụp ảnh tại Cầu Vàng, tham quan Làng Pháp',
                'meals' => 'Ăn trưa tại nhà hàng trên Bà Nà Hills, ăn tối tại nhà hàng Đà Nẵng',
                'accommodation' => 'Khách sạn 4* tại trung tâm Đà Nẵng',
                'transportation' => 'Máy bay, xe du lịch 29 chỗ, cáp treo',
                'notes' => 'Mang theo áo khoác mỏng, giày đi bộ. Nhiệt độ trên Bà Nà Hills thấp hơn dưới thành phố.',
            ],
            [
                'day_number' => 2,
                'title' => 'Ngày 2: Hội An - Phố cổ - Chợ đêm',
                'description' => 'Tham quan phố cổ Hội An, các di tích lịch sử và chợ đêm Hội An',
                'location' => 'Hội An, Quảng Nam',
                'start_time' => '08:00',
                'end_time' => '22:00',
                'meeting_point' => 'Sảnh khách sạn',
                'activities' => 'Tham quan Chùa Cầu, Nhà cổ Tấn Ký, Hội quán Phúc Kiến, làm đèn lồng, đi thuyền thả hoa đăng, tham quan chợ đêm Hội An',
                'meals' => 'Ăn sáng tại khách sạn, ăn trưa tại nhà hàng Hội An, ăn tối tại chợ đêm',
                'accommodation' => 'Khách sạn 4* tại trung tâm Đà Nẵng',
                'transportation' => 'Xe du lịch 29 chỗ, thuyền',
                'notes' => 'Mang theo máy ảnh. Chợ đêm Hội An rất đẹp, nên mua đèn lồng làm quà.',
            ],
            [
                'day_number' => 3,
                'title' => 'Ngày 3: Bãi biển Mỹ Khê - Về TP.HCM',
                'description' => 'Tắm biển Mỹ Khê, mua sắm đặc sản, trở về TP.HCM',
                'location' => 'Bãi biển Mỹ Khê, Đà Nẵng',
                'start_time' => '08:00',
                'end_time' => '18:00',
                'meeting_point' => 'Sảnh khách sạn',
                'activities' => 'Tắm biển Mỹ Khê, tham quan chợ Hàn, mua sắm đặc sản Đà Nẵng, di chuyển về TP.HCM',
                'meals' => 'Ăn sáng tại khách sạn, ăn trưa tại nhà hàng Đà Nẵng',
                'accommodation' => 'Không',
                'transportation' => 'Xe du lịch 29 chỗ, máy bay',
                'notes' => 'Trả phòng khách sạn trước 12:00. Dự kiến về đến TP.HCM lúc 18:00.',
            ],
        ]);

        // Tour 2: Phú Quốc 4N3Đ
        $tour2 = $this->createTour([
            'category_id' => $categoryBienDao->id,
            'title' => 'Phú Quốc 4N3Đ',
            'short_description' => 'Thiên đường biển đảo với bãi biển tuyệt đẹp',
            'description' => 'Tour Phú Quốc 4 ngày 3 đêm khám phá đảo ngọc với các hoạt động thú vị như lặn biển, câu cá, tham quan làng chài...',
            'price' => 7800000,
            'price_adult' => 7800000,
            'price_child' => 6500000,
            'location' => 'Phú Quốc, Kiên Giang',
            'duration' => '4 ngày 3 đêm',
            'duration_days' => 4,
            'duration_nights' => 3,
            'available_seats' => 25,
            'status' => 'active',
        ], [
            [
                'day_number' => 1,
                'title' => 'Ngày 1: Khởi hành - Phú Quốc - Bãi Sao',
                'description' => 'Khởi hành từ TP.HCM, di chuyển đến Phú Quốc, tham quan Bãi Sao',
                'location' => 'Phú Quốc, Kiên Giang',
                'start_time' => '06:00',
                'end_time' => '19:00',
                'meeting_point' => 'Sân bay Tân Sơn Nhất',
                'activities' => 'Di chuyển bằng máy bay, check-in khách sạn, tham quan Bãi Sao, tắm biển',
                'meals' => 'Ăn trưa tại nhà hàng Phú Quốc, ăn tối tại khách sạn',
                'accommodation' => 'Resort 4* tại Bãi Sao',
                'transportation' => 'Máy bay, xe du lịch 29 chỗ',
                'notes' => 'Mang theo kem chống nắng, đồ bơi. Nhiệt độ Phú Quốc cao, nắng gắt.',
            ],
            [
                'day_number' => 2,
                'title' => 'Ngày 2: Tour đảo Nam Du - Lặn biển',
                'description' => 'Tham quan đảo Nam Du, lặn biển ngắm san hô, câu cá',
                'location' => 'Đảo Nam Du, Phú Quốc',
                'start_time' => '07:00',
                'end_time' => '17:00',
                'meeting_point' => 'Bến tàu An Thới',
                'activities' => 'Đi tàu ra đảo Nam Du, lặn biển ngắm san hô, câu cá, tắm biển, chụp ảnh',
                'meals' => 'Ăn sáng tại resort, ăn trưa trên tàu (hải sản tươi sống), ăn tối tại nhà hàng',
                'accommodation' => 'Resort 4* tại Bãi Sao',
                'transportation' => 'Tàu cao tốc, xe du lịch',
                'notes' => 'Mang theo đồ bơi, khăn tắm. Có thể say sóng, nên chuẩn bị thuốc chống say.',
            ],
            [
                'day_number' => 3,
                'title' => 'Ngày 3: Vinpearl Safari - Chợ đêm Dinh Cậu',
                'description' => 'Tham quan Vinpearl Safari, chợ đêm Dinh Cậu',
                'location' => 'Vinpearl Safari, Dinh Cậu, Phú Quốc',
                'start_time' => '08:00',
                'end_time' => '22:00',
                'meeting_point' => 'Sảnh resort',
                'activities' => 'Tham quan Vinpearl Safari, xem show động vật, tham quan Dinh Cậu, chợ đêm Dinh Cậu, mua sắm',
                'meals' => 'Ăn sáng tại resort, ăn trưa tại Vinpearl, ăn tối tại chợ đêm',
                'accommodation' => 'Resort 4* tại Bãi Sao',
                'transportation' => 'Xe du lịch 29 chỗ',
                'notes' => 'Mang theo máy ảnh. Chợ đêm Dinh Cậu có nhiều món ăn ngon và đồ lưu niệm.',
            ],
            [
                'day_number' => 4,
                'title' => 'Ngày 4: Bãi Dài - Về TP.HCM',
                'description' => 'Tham quan Bãi Dài, mua sắm đặc sản, trở về TP.HCM',
                'location' => 'Bãi Dài, Phú Quốc',
                'start_time' => '08:00',
                'end_time' => '18:00',
                'meeting_point' => 'Sảnh resort',
                'activities' => 'Tắm biển Bãi Dài, tham quan làng chài, mua sắm đặc sản Phú Quốc (nước mắm, hồ tiêu), di chuyển về TP.HCM',
                'meals' => 'Ăn sáng tại resort, ăn trưa tại nhà hàng Phú Quốc',
                'accommodation' => 'Không',
                'transportation' => 'Xe du lịch 29 chỗ, máy bay',
                'notes' => 'Trả phòng resort trước 12:00. Dự kiến về đến TP.HCM lúc 18:00.',
            ],
        ]);

        // Tour 3: Sapa 2N1Đ
        $tour3 = $this->createTour([
            'category_id' => $categoryTrongNuoc->id,
            'title' => 'Sapa 2N1Đ',
            'short_description' => 'Khám phá vùng núi Tây Bắc với ruộng bậc thang',
            'description' => 'Tour Sapa 2 ngày 1 đêm tham quan ruộng bậc thang, làng dân tộc, chợ tình Sapa...',
            'price' => 3200000,
            'price_adult' => 3200000,
            'price_child' => 2500000,
            'location' => 'Sapa, Lào Cai',
            'duration' => '2 ngày 1 đêm',
            'duration_days' => 2,
            'duration_nights' => 1,
            'available_seats' => 20,
            'status' => 'active',
        ], [
            [
                'day_number' => 1,
                'title' => 'Ngày 1: Hà Nội - Sapa - Bản Cát Cát',
                'description' => 'Khởi hành từ Hà Nội đi Sapa, thăm quan bản Cát Cát',
                'location' => 'Sapa, Lào Cai',
                'start_time' => '06:00',
                'end_time' => '18:00',
                'meeting_point' => 'Văn phòng công ty - 123 Đường ABC, Hà Nội',
                'activities' => 'Di chuyển bằng xe khách, thăm quan bản Cát Cát, tìm hiểu văn hóa dân tộc H\'Mông, check-in khách sạn',
                'meals' => 'Ăn trưa tại nhà hàng địa phương, ăn tối tại khách sạn',
                'accommodation' => 'Khách sạn 3* tại trung tâm thị trấn Sapa',
                'transportation' => 'Xe khách 45 chỗ đời mới, có điều hòa',
                'notes' => 'Mang theo áo ấm, giày đi bộ đường dài. Thời tiết Sapa có thể thay đổi đột ngột.',
            ],
            [
                'day_number' => 2,
                'title' => 'Ngày 2: Fansipan - Chợ Sapa - Về Hà Nội',
                'description' => 'Chinh phục đỉnh Fansipan, tham quan chợ Sapa, trở về Hà Nội',
                'location' => 'Đỉnh Fansipan, Sapa, Hà Nội',
                'start_time' => '07:30',
                'end_time' => '20:00',
                'meeting_point' => 'Sảnh khách sạn',
                'activities' => 'Đi cáp treo lên đỉnh Fansipan, chụp ảnh tại đỉnh núi cao nhất Việt Nam, tham quan chợ Sapa, mua sắm đặc sản, di chuyển về Hà Nội',
                'meals' => 'Ăn sáng tại khách sạn, ăn trưa tại nhà hàng trên núi, ăn tối trên đường về',
                'accommodation' => 'Không',
                'transportation' => 'Xe du lịch 16 chỗ, cáp treo Muong Hoa, xe khách về Hà Nội',
                'notes' => 'Trả phòng khách sạn trước 12:00. Dự kiến về đến Hà Nội lúc 20:00.',
            ],
        ]);

        $this->command->info('Đã tạo thành công 3 tour với đầy đủ lịch trình và ngày khởi hành!');
    }

    /**
     * Tạo tour với lịch trình và ngày khởi hành
     */
    private function createTour(array $tourData, array $schedules): Tour
    {
        // Tạo tour
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

        // Xóa lịch trình cũ nếu có
        TourSchedule::where('tour_id', $tour->id)->delete();

        // Tạo lịch trình từng ngày
        foreach ($schedules as $scheduleData) {
            TourSchedule::create(array_merge($scheduleData, [
                'tour_id' => $tour->id,
            ]));
        }

        // Xóa ngày khởi hành cũ nếu có
        TourDeparture::where('tour_id', $tour->id)->delete();

        // Tạo 3 ngày khởi hành mẫu (cách nhau 7 ngày)
        for ($i = 0; $i < 3; $i++) {
            $departureDate = Carbon::now()->addDays(7 + ($i * 7));
            
            TourDeparture::create([
                'tour_id' => $tour->id,
                'departure_date' => $departureDate,
                'departure_time' => '06:00',
                'departure_location' => 'Văn phòng công ty - 123 Nguyễn Huệ, Q1, TP.HCM',
                'seats_total' => $tour->available_seats,
                'seats_available' => $tour->available_seats,
                'price' => $tour->price_adult ?? $tour->price,
                'child_price' => $tour->price_child ?? ($tour->price * 0.8),
                'status' => 'available',
                'meeting_point' => 'Văn phòng công ty - 123 Nguyễn Huệ, Q1, TP.HCM',
                'departure_instructions' => 'Quý khách có mặt trước 30 phút. Mang theo CMND/CCCD và đồ dùng cá nhân. Liên hệ HDV qua số điện thoại được cung cấp.',
            ]);
        }

        $this->command->info("✓ Đã tạo tour: {$tour->title} với " . count($schedules) . " ngày lịch trình và 3 ngày khởi hành");

        return $tour;
    }
}
