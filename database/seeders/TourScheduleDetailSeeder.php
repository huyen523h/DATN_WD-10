<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tour;
use App\Models\TourSchedule;
use App\Models\TourDeparture;
use App\Models\Guide;

class TourScheduleDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sử dụng guides đã có sẵn trong database
        $this->command->info('Sử dụng guides đã có sẵn trong database...');

        // Lấy tour đầu tiên để tạo lịch trình mẫu
        $tour = Tour::first();
        if (!$tour) {
            $this->command->info('Không có tour nào trong database. Vui lòng tạo tour trước.');
            return;
        }

        // Xóa lịch trình cũ nếu có
        TourSchedule::where('tour_id', $tour->id)->delete();

        // Tạo lịch trình chi tiết cho tour 3 ngày 2 đêm Sapa
        $schedules = [
            [
                'tour_id' => $tour->id,
                'day_number' => 1,
                'title' => 'Hà Nội - Sapa - Thăm quan bản Cát Cát',
                'description' => 'Khởi hành từ Hà Nội đi Sapa, thăm quan bản Cát Cát, check-in khách sạn',
                'location' => 'Sapa, Lào Cai',
                'start_time' => '06:00',
                'end_time' => '18:00',
                'meeting_point' => 'Văn phòng công ty - 123 Đường ABC, Hà Nội',
                'activities' => 'Di chuyển bằng xe khách, thăm quan bản Cát Cát, tìm hiểu văn hóa dân tộc H\'Mông',
                'meals' => 'Ăn trưa tại nhà hàng địa phương, ăn tối tại khách sạn',
                'accommodation' => 'Khách sạn 3* tại trung tâm thị trấn Sapa',
                'transportation' => 'Xe khách 45 chỗ đời mới, có điều hòa',
                'notes' => 'Mang theo áo ấm, giày đi bộ đường dài. Thời tiết Sapa có thể thay đổi đột ngột.',
                'images' => json_encode([
                    '/images/tours/sapa-day1-1.jpg',
                    '/images/tours/sapa-day1-2.jpg'
                ])
            ],
            [
                'tour_id' => $tour->id,
                'day_number' => 2,
                'title' => 'Chinh phục đỉnh Fansipan - Cáp treo Muong Hoa',
                'description' => 'Trải nghiệm cáp treo lên đỉnh Fansipan, ngắm cảnh núi rừng Tây Bắc',
                'location' => 'Đỉnh Fansipan, Sapa',
                'start_time' => '07:30',
                'end_time' => '17:00',
                'meeting_point' => 'Sảnh khách sạn',
                'activities' => 'Đi cáp treo lên đỉnh Fansipan, chụp ảnh tại đỉnh núi cao nhất Việt Nam, thăm quan Khu du lịch Sun World Fansipan Legend',
                'meals' => 'Ăn sáng tại khách sạn, ăn trưa tại nhà hàng trên núi, ăn tối tại thị trấn Sapa',
                'accommodation' => 'Khách sạn 3* tại trung tâm thị trấn Sapa',
                'transportation' => 'Xe du lịch 16 chỗ, cáp treo Muong Hoa',
                'notes' => 'Mang theo áo ấm, có thể có sương mù. Vé cáp treo đã bao gồm trong tour.',
                'images' => json_encode([
                    '/images/tours/fansipan-1.jpg',
                    '/images/tours/fansipan-2.jpg'
                ])
            ],
            [
                'tour_id' => $tour->id,
                'day_number' => 3,
                'title' => 'Sapa - Hà Nội - Kết thúc chuyến đi',
                'description' => 'Tham quan chợ Sapa, mua sắm đặc sản, trở về Hà Nội',
                'location' => 'Chợ Sapa, Hà Nội',
                'start_time' => '08:00',
                'end_time' => '18:00',
                'meeting_point' => 'Sảnh khách sạn',
                'activities' => 'Tham quan chợ Sapa, mua sắm đặc sản địa phương, di chuyển về Hà Nội',
                'meals' => 'Ăn sáng tại khách sạn, ăn trưa trên đường về',
                'accommodation' => 'Không',
                'transportation' => 'Xe khách 45 chỗ về Hà Nội',
                'notes' => 'Trả phòng khách sạn trước 12:00. Dự kiến về đến Hà Nội lúc 18:00.',
                'images' => json_encode([
                    '/images/tours/sapa-market.jpg'
                ])
            ]
        ];

        foreach ($schedules as $schedule) {
            TourSchedule::create($schedule);
        }

        // Cập nhật thông tin chi tiết cho tour departure
        $departure = TourDeparture::where('tour_id', $tour->id)->first();
        if ($departure) {
            $guide = \App\Models\User::where('role', 'guide')->first(); // Lấy user có role guide
            $backupGuide = \App\Models\User::where('role', 'guide')->skip(1)->first(); // Lấy guide thứ 2
            
            $departure->update([
                'departure_time' => '06:00',
                'departure_location' => 'Văn phòng công ty - 123 Đường ABC, Quận Ba Đình, Hà Nội',
                'departure_instructions' => 'Quý khách có mặt trước 30 phút. Mang theo CMND/CCCD, áo ấm và giày đi bộ. Liên hệ HDV qua số điện thoại được cung cấp.',
                'guide_id' => $guide?->id,
                'backup_guide_id' => $backupGuide?->id,
                'emergency_contact' => 'Trung tâm điều hành tour',
                'emergency_phone' => '1900-1234',
                'special_notes' => 'Tour có thể thay đổi lịch trình tùy thuộc vào thời tiết. Thông báo sẽ được gửi trước 24h nếu có thay đổi.',
                'preparation_status' => 'ready'
            ]);
        }

        $this->command->info('Đã tạo thành công dữ liệu mẫu cho lịch trình tour chi tiết!');
    }
}