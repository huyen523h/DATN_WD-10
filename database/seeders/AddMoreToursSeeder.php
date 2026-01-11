<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tour;
use App\Models\Category;
use App\Models\TourImage;
use App\Models\TourSchedule;
use App\Models\TourDeparture;
use Carbon\Carbon;

class AddMoreToursSeeder extends Seeder
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
        $categoryNuocNgoai = $categories->where('name', 'Du lịch nước ngoài')->first();
        
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
        
        if (!$categoryNuocNgoai) {
            $categoryNuocNgoai = Category::firstOrCreate(
                ['name' => 'Du lịch nước ngoài'],
                ['description' => 'Các tour du lịch quốc tế']
            );
        }

        // Tour 1: Nha Trang - Đà Lạt 4N3Đ
        $tour1 = $this->createTour([
            'category_id' => $categoryTrongNuoc->id,
            'title' => 'Nha Trang - Đà Lạt 4N3Đ',
            'short_description' => 'Kết hợp biển xanh và cao nguyên mát mẻ',
            'description' => 'Tour Nha Trang - Đà Lạt 4 ngày 3 đêm khám phá thành phố biển Nha Trang với các hoạt động lặn biển, tham quan Vinpearl, sau đó lên Đà Lạt thưởng thức không khí mát mẻ và tham quan các điểm du lịch nổi tiếng.',
            'price' => 6500000,
            'price_adult' => 6500000,
            'price_child' => 5200000,
            'location' => 'Nha Trang, Đà Lạt',
            'duration' => '4 ngày 3 đêm',
            'duration_days' => 4,
            'duration_nights' => 3,
            'available_seats' => 28,
            'status' => 'active',
        ], [
            [
                'day_number' => 1,
                'title' => 'Ngày 1: TP.HCM - Nha Trang - Vinpearl',
                'description' => 'Khởi hành từ TP.HCM, di chuyển đến Nha Trang, tham quan Vinpearl Land',
                'location' => 'Nha Trang, Khánh Hòa',
                'start_time' => '05:00',
                'end_time' => '20:00',
                'meeting_point' => 'Văn phòng công ty - 123 Nguyễn Huệ, Q1, TP.HCM',
                'activities' => 'Di chuyển bằng máy bay, check-in khách sạn, tham quan Vinpearl Land, vui chơi công viên giải trí, xem show biểu diễn',
                'meals' => 'Ăn trưa tại nhà hàng Nha Trang, ăn tối tại khách sạn',
                'accommodation' => 'Khách sạn 4* tại trung tâm Nha Trang',
                'transportation' => 'Máy bay, xe du lịch 29 chỗ',
                'notes' => 'Mang theo đồ bơi, kem chống nắng. Nhiệt độ Nha Trang cao, nắng gắt.',
            ],
            [
                'day_number' => 2,
                'title' => 'Ngày 2: Lặn biển - Tham quan đảo',
                'description' => 'Lặn biển ngắm san hô, tham quan các đảo xung quanh Nha Trang',
                'location' => 'Đảo Hòn Mun, Nha Trang',
                'start_time' => '07:00',
                'end_time' => '17:00',
                'meeting_point' => 'Bến tàu Cầu Đá',
                'activities' => 'Đi tàu ra đảo Hòn Mun, lặn biển ngắm san hô, tắm biển, chụp ảnh, tham quan đảo Hòn Tằm',
                'meals' => 'Ăn sáng tại khách sạn, ăn trưa trên tàu (hải sản tươi sống), ăn tối tại nhà hàng',
                'accommodation' => 'Khách sạn 4* tại trung tâm Nha Trang',
                'transportation' => 'Tàu cao tốc, xe du lịch',
                'notes' => 'Mang theo đồ bơi, khăn tắm. Có thể say sóng, nên chuẩn bị thuốc chống say.',
            ],
            [
                'day_number' => 3,
                'title' => 'Ngày 3: Nha Trang - Đà Lạt',
                'description' => 'Di chuyển lên Đà Lạt, tham quan các điểm du lịch nổi tiếng',
                'location' => 'Đà Lạt, Lâm Đồng',
                'start_time' => '08:00',
                'end_time' => '20:00',
                'meeting_point' => 'Sảnh khách sạn',
                'activities' => 'Di chuyển lên Đà Lạt, tham quan Thung lũng Tình yêu, Hồ Xuân Hương, chợ đêm Đà Lạt',
                'meals' => 'Ăn sáng tại khách sạn, ăn trưa tại nhà hàng Đà Lạt, ăn tối tại chợ đêm',
                'accommodation' => 'Khách sạn 4* tại trung tâm Đà Lạt',
                'transportation' => 'Xe du lịch 29 chỗ',
                'notes' => 'Mang theo áo khoác mỏng. Nhiệt độ Đà Lạt thấp hơn Nha Trang.',
            ],
            [
                'day_number' => 4,
                'title' => 'Ngày 4: Đà Lạt - Về TP.HCM',
                'description' => 'Tham quan các điểm cuối cùng, mua sắm đặc sản, trở về TP.HCM',
                'location' => 'Đà Lạt, TP.HCM',
                'start_time' => '08:00',
                'end_time' => '18:00',
                'meeting_point' => 'Sảnh khách sạn',
                'activities' => 'Tham quan Dinh Bảo Đại, Vườn hoa thành phố, mua sắm đặc sản Đà Lạt (dâu tây, mứt, rượu vang), di chuyển về TP.HCM',
                'meals' => 'Ăn sáng tại khách sạn, ăn trưa tại nhà hàng Đà Lạt',
                'accommodation' => 'Không',
                'transportation' => 'Xe du lịch 29 chỗ, máy bay',
                'notes' => 'Trả phòng khách sạn trước 12:00. Dự kiến về đến TP.HCM lúc 18:00.',
            ],
        ]);

        // Tour 2: Hạ Long - Cát Bà 3N2Đ
        $tour2 = $this->createTour([
            'category_id' => $categoryBienDao->id,
            'title' => 'Hạ Long - Cát Bà 3N2Đ',
            'short_description' => 'Vịnh Hạ Long kỳ quan thiên nhiên thế giới',
            'description' => 'Tour Hạ Long - Cát Bà 3 ngày 2 đêm du thuyền trên vịnh Hạ Long, tham quan hang động, đảo Cát Bà với các hoạt động leo núi, tắm biển và khám phá rừng quốc gia.',
            'price' => 4800000,
            'price_adult' => 4800000,
            'price_child' => 3800000,
            'location' => 'Hạ Long, Cát Bà, Quảng Ninh',
            'duration' => '3 ngày 2 đêm',
            'duration_days' => 3,
            'duration_nights' => 2,
            'available_seats' => 35,
            'status' => 'active',
        ], [
            [
                'day_number' => 1,
                'title' => 'Ngày 1: Hà Nội - Hạ Long - Du thuyền',
                'description' => 'Khởi hành từ Hà Nội, di chuyển đến Hạ Long, lên du thuyền',
                'location' => 'Vịnh Hạ Long, Quảng Ninh',
                'start_time' => '07:00',
                'end_time' => '20:00',
                'meeting_point' => 'Văn phòng công ty - 123 Đường ABC, Hà Nội',
                'activities' => 'Di chuyển bằng xe khách, lên du thuyền, tham quan hang Sửng Sốt, chèo kayak, tắm biển',
                'meals' => 'Ăn trưa trên du thuyền, ăn tối trên du thuyền',
                'accommodation' => 'Du thuyền 3* trên vịnh Hạ Long',
                'transportation' => 'Xe khách 45 chỗ, du thuyền',
                'notes' => 'Mang theo đồ bơi, kem chống nắng. Có thể say sóng, nên chuẩn bị thuốc chống say.',
            ],
            [
                'day_number' => 2,
                'title' => 'Ngày 2: Đảo Cát Bà - Rừng quốc gia',
                'description' => 'Tham quan đảo Cát Bà, leo núi, khám phá rừng quốc gia',
                'location' => 'Đảo Cát Bà, Hải Phòng',
                'start_time' => '08:00',
                'end_time' => '18:00',
                'meeting_point' => 'Bến tàu Cát Bà',
                'activities' => 'Đi tàu ra đảo Cát Bà, leo núi Cát Bà, tham quan rừng quốc gia, tắm biển Cát Cò, chụp ảnh',
                'meals' => 'Ăn sáng trên du thuyền, ăn trưa tại nhà hàng Cát Bà, ăn tối tại nhà hàng',
                'accommodation' => 'Khách sạn 3* tại đảo Cát Bà',
                'transportation' => 'Tàu, xe du lịch',
                'notes' => 'Mang theo giày đi bộ, áo khoác. Leo núi cần thể lực tốt.',
            ],
            [
                'day_number' => 3,
                'title' => 'Ngày 3: Cát Bà - Hạ Long - Về Hà Nội',
                'description' => 'Tham quan điểm cuối cùng, mua sắm hải sản, trở về Hà Nội',
                'location' => 'Cát Bà, Hạ Long, Hà Nội',
                'start_time' => '08:00',
                'end_time' => '20:00',
                'meeting_point' => 'Sảnh khách sạn',
                'activities' => 'Tham quan làng chài Cát Bà, mua sắm hải sản tươi sống, di chuyển về Hà Nội',
                'meals' => 'Ăn sáng tại khách sạn, ăn trưa tại nhà hàng Hạ Long',
                'accommodation' => 'Không',
                'transportation' => 'Tàu, xe khách về Hà Nội',
                'notes' => 'Trả phòng khách sạn trước 12:00. Dự kiến về đến Hà Nội lúc 20:00.',
            ],
        ]);

        // Tour 3: Huế - Quảng Bình 4N3Đ
        $tour3 = $this->createTour([
            'category_id' => $categoryTrongNuoc->id,
            'title' => 'Huế - Quảng Bình 4N3Đ',
            'short_description' => 'Cố đô Huế và hang động kỳ vĩ',
            'description' => 'Tour Huế - Quảng Bình 4 ngày 3 đêm khám phá cố đô Huế với Đại Nội, lăng tẩm các vua, sau đó đến Quảng Bình tham quan hang Sơn Đoòng, Phong Nha - Kẻ Bàng.',
            'price' => 5800000,
            'price_adult' => 5800000,
            'price_child' => 4600000,
            'location' => 'Huế, Quảng Bình',
            'duration' => '4 ngày 3 đêm',
            'duration_days' => 4,
            'duration_nights' => 3,
            'available_seats' => 25,
            'status' => 'active',
        ], [
            [
                'day_number' => 1,
                'title' => 'Ngày 1: TP.HCM - Huế - Đại Nội',
                'description' => 'Khởi hành từ TP.HCM, di chuyển đến Huế, tham quan Đại Nội',
                'location' => 'Huế, Thừa Thiên Huế',
                'start_time' => '06:00',
                'end_time' => '20:00',
                'meeting_point' => 'Sân bay Tân Sơn Nhất',
                'activities' => 'Di chuyển bằng máy bay, check-in khách sạn, tham quan Đại Nội, Hoàng thành, Tử Cấm Thành',
                'meals' => 'Ăn trưa tại nhà hàng Huế, ăn tối tại khách sạn',
                'accommodation' => 'Khách sạn 4* tại trung tâm Huế',
                'transportation' => 'Máy bay, xe du lịch 29 chỗ',
                'notes' => 'Mang theo máy ảnh. Đại Nội rất rộng, nên đi giày thoải mái.',
            ],
            [
                'day_number' => 2,
                'title' => 'Ngày 2: Lăng tẩm các vua - Sông Hương',
                'description' => 'Tham quan lăng tẩm các vua, đi thuyền trên sông Hương',
                'location' => 'Huế, Thừa Thiên Huế',
                'start_time' => '08:00',
                'end_time' => '20:00',
                'meeting_point' => 'Sảnh khách sạn',
                'activities' => 'Tham quan Lăng Khải Định, Lăng Tự Đức, Lăng Minh Mạng, đi thuyền trên sông Hương, nghe ca Huế',
                'meals' => 'Ăn sáng tại khách sạn, ăn trưa tại nhà hàng, ăn tối trên thuyền',
                'accommodation' => 'Khách sạn 4* tại trung tâm Huế',
                'transportation' => 'Xe du lịch 29 chỗ, thuyền',
                'notes' => 'Mang theo máy ảnh. Ca Huế là di sản văn hóa phi vật thể.',
            ],
            [
                'day_number' => 3,
                'title' => 'Ngày 3: Huế - Quảng Bình - Phong Nha',
                'description' => 'Di chuyển đến Quảng Bình, tham quan động Phong Nha',
                'location' => 'Phong Nha - Kẻ Bàng, Quảng Bình',
                'start_time' => '08:00',
                'end_time' => '18:00',
                'meeting_point' => 'Sảnh khách sạn',
                'activities' => 'Di chuyển đến Quảng Bình, tham quan động Phong Nha, đi thuyền trong động, tham quan động Thiên Đường',
                'meals' => 'Ăn sáng tại khách sạn, ăn trưa tại nhà hàng Quảng Bình, ăn tối tại khách sạn',
                'accommodation' => 'Khách sạn 3* tại Quảng Bình',
                'transportation' => 'Xe du lịch 29 chỗ, thuyền',
                'notes' => 'Mang theo áo khoác mỏng. Nhiệt độ trong động thấp hơn bên ngoài.',
            ],
            [
                'day_number' => 4,
                'title' => 'Ngày 4: Quảng Bình - Về TP.HCM',
                'description' => 'Tham quan điểm cuối cùng, mua sắm đặc sản, trở về TP.HCM',
                'location' => 'Quảng Bình, TP.HCM',
                'start_time' => '08:00',
                'end_time' => '18:00',
                'meeting_point' => 'Sảnh khách sạn',
                'activities' => 'Tham quan bãi biển Nhật Lệ, mua sắm đặc sản Quảng Bình, di chuyển về TP.HCM',
                'meals' => 'Ăn sáng tại khách sạn, ăn trưa tại nhà hàng',
                'accommodation' => 'Không',
                'transportation' => 'Xe du lịch 29 chỗ, máy bay',
                'notes' => 'Trả phòng khách sạn trước 12:00. Dự kiến về đến TP.HCM lúc 18:00.',
            ],
        ]);

        // Tour 4: Mù Cang Chải 3N2Đ
        $tour4 = $this->createTour([
            'category_id' => $categoryTrongNuoc->id,
            'title' => 'Mù Cang Chải 3N2Đ',
            'short_description' => 'Ruộng bậc thang vàng mùa lúa chín',
            'description' => 'Tour Mù Cang Chải 3 ngày 2 đêm ngắm ruộng bậc thang vàng rực mùa lúa chín, tham quan làng dân tộc Mông, chợ phiên địa phương và trải nghiệm văn hóa vùng cao Tây Bắc.',
            'price' => 4200000,
            'price_adult' => 4200000,
            'price_child' => 3300000,
            'location' => 'Mù Cang Chải, Yên Bái',
            'duration' => '3 ngày 2 đêm',
            'duration_days' => 3,
            'duration_nights' => 2,
            'available_seats' => 20,
            'status' => 'active',
        ], [
            [
                'day_number' => 1,
                'title' => 'Ngày 1: Hà Nội - Mù Cang Chải',
                'description' => 'Khởi hành từ Hà Nội, di chuyển đến Mù Cang Chải',
                'location' => 'Mù Cang Chải, Yên Bái',
                'start_time' => '06:00',
                'end_time' => '18:00',
                'meeting_point' => 'Văn phòng công ty - 123 Đường ABC, Hà Nội',
                'activities' => 'Di chuyển bằng xe khách, tham quan ruộng bậc thang La Pán Tẩn, chụp ảnh, check-in khách sạn',
                'meals' => 'Ăn trưa tại nhà hàng địa phương, ăn tối tại khách sạn',
                'accommodation' => 'Homestay tại Mù Cang Chải',
                'transportation' => 'Xe khách 45 chỗ đời mới, có điều hòa',
                'notes' => 'Mang theo áo ấm, giày đi bộ. Thời tiết vùng cao có thể thay đổi đột ngột.',
            ],
            [
                'day_number' => 2,
                'title' => 'Ngày 2: Ruộng bậc thang - Làng dân tộc',
                'description' => 'Tham quan ruộng bậc thang đẹp nhất, làng dân tộc Mông',
                'location' => 'Mù Cang Chải, Yên Bái',
                'start_time' => '07:00',
                'end_time' => '20:00',
                'meeting_point' => 'Homestay',
                'activities' => 'Tham quan ruộng bậc thang Đèo Khau Phạ, làng dân tộc Mông, tìm hiểu văn hóa địa phương, chụp ảnh, tham quan chợ phiên',
                'meals' => 'Ăn sáng tại homestay, ăn trưa tại nhà hàng địa phương, ăn tối tại homestay',
                'accommodation' => 'Homestay tại Mù Cang Chải',
                'transportation' => 'Xe du lịch 16 chỗ',
                'notes' => 'Mang theo máy ảnh. Ruộng bậc thang đẹp nhất vào sáng sớm và chiều tối.',
            ],
            [
                'day_number' => 3,
                'title' => 'Ngày 3: Mù Cang Chải - Về Hà Nội',
                'description' => 'Tham quan điểm cuối cùng, mua sắm đặc sản, trở về Hà Nội',
                'location' => 'Mù Cang Chải, Hà Nội',
                'start_time' => '08:00',
                'end_time' => '20:00',
                'meeting_point' => 'Homestay',
                'activities' => 'Tham quan ruộng bậc thang Tú Lệ, mua sắm đặc sản vùng cao (gạo nếp, mật ong, thịt gà), di chuyển về Hà Nội',
                'meals' => 'Ăn sáng tại homestay, ăn trưa tại nhà hàng địa phương',
                'accommodation' => 'Không',
                'transportation' => 'Xe khách về Hà Nội',
                'notes' => 'Trả phòng homestay trước 12:00. Dự kiến về đến Hà Nội lúc 20:00.',
            ],
        ]);

        $this->command->info('Đã tạo thành công 4 tour mới với đầy đủ lịch trình và ngày khởi hành!');
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

        // Lấy ngày 14 của tháng hiện tại hoặc tháng tới
        $now = Carbon::now();
        $day14 = Carbon::create($now->year, $now->month, 14);
        
        // Nếu ngày 14 đã qua trong tháng này, dùng ngày 14 tháng sau
        if ($day14->isPast()) {
            $day14 = $day14->addMonth();
        }

        // Kiểm tra xem đã có departure vào ngày 14 chưa
        $existing = TourDeparture::where('tour_id', $tour->id)
            ->whereDate('departure_date', $day14->toDateString())
            ->first();
        
        if (!$existing) {
            $seatsTotal = $tour->available_seats ?? 30;
            $price = $tour->price_adult ?? $tour->price ?? 5000000;
            $childPrice = $tour->price_child ?? ($price * 0.8);
            
            TourDeparture::create([
                'tour_id' => $tour->id,
                'departure_date' => $day14->toDateString(),
                'departure_time' => '06:00',
                'departure_location' => 'Văn phòng công ty - 123 Nguyễn Huệ, Q1, TP.HCM',
                'seats_total' => $seatsTotal,
                'seats_available' => $seatsTotal,
                'price' => $price,
                'child_price' => $childPrice,
                'status' => 'available',
                'meeting_point' => 'Văn phòng công ty - 123 Nguyễn Huệ, Q1, TP.HCM',
                'departure_instructions' => 'Quý khách có mặt trước 30 phút (05:30). Mang theo CMND/CCCD và đồ dùng cá nhân. Liên hệ HDV qua số điện thoại được cung cấp.',
            ]);
        }

        $this->command->info("✓ Đã tạo tour: {$tour->title} với " . count($schedules) . " ngày lịch trình và ngày khởi hành {$day14->format('d/m/Y')}");

        return $tour;
    }
}
