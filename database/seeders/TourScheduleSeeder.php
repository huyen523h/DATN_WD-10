<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tour;
use App\Models\TourSchedule;

class TourScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy tất cả các tour chưa có lịch trình
        $toursWithoutSchedule = Tour::whereDoesntHave('schedules')
            ->where('status', 'active')
            ->get();
        
        if ($toursWithoutSchedule->isEmpty()) {
            $this->command->info('Tất cả các tour đã có lịch trình!');
            return;
        }
        
        $this->command->info("Tìm thấy {$toursWithoutSchedule->count()} tour chưa có lịch trình. Đang thêm lịch trình...");
        
        foreach ($toursWithoutSchedule as $tour) {
            $this->createScheduleForTour($tour);
        }
        
        $this->command->info("Hoàn thành! Đã thêm lịch trình cho {$toursWithoutSchedule->count()} tour.");
    }
    
    /**
     * Tạo lịch trình cho tour
     */
    private function createScheduleForTour(Tour $tour): void
    {
        $days = $tour->duration_days ?? 3; // Mặc định 3 ngày nếu không có
        $location = $tour->location ?? 'Điểm đến';
        $title = $tour->title;
        
        $schedules = [];
        
        for ($day = 1; $day <= $days; $day++) {
            if ($day == 1) {
                // Ngày đầu: Khởi hành
                $schedules[] = [
                    'tour_id' => $tour->id,
                    'day_number' => $day,
                    'title' => "Ngày {$day}: Khởi hành - {$location}",
                    'description' => "Khởi hành từ điểm xuất phát, di chuyển đến {$location}, check-in khách sạn và tham quan các điểm nổi tiếng đầu tiên.",
                    'location' => $location,
                    'start_time' => '06:00',
                    'end_time' => '20:00',
                    'meeting_point' => 'Văn phòng công ty - 123 Nguyễn Huệ, Q1, TP.HCM',
                    'activities' => "Di chuyển đến {$location}, check-in khách sạn, tham quan các điểm du lịch đầu tiên, chụp ảnh lưu niệm",
                    'meals' => 'Ăn trưa tại nhà hàng địa phương, ăn tối tại khách sạn',
                    'accommodation' => 'Khách sạn 3-4* tại trung tâm',
                    'transportation' => 'Xe du lịch 29-45 chỗ, có điều hòa',
                    'notes' => 'Mang theo đồ dùng cá nhân, CMND/CCCD. Thời gian có thể thay đổi tùy tình hình thực tế.',
                ];
            } elseif ($day == $days) {
                // Ngày cuối: Kết thúc tour
                $schedules[] = [
                    'tour_id' => $tour->id,
                    'day_number' => $day,
                    'title' => "Ngày {$day}: {$location} - Kết thúc tour",
                    'description' => "Tham quan điểm cuối cùng, mua sắm đặc sản địa phương, trả phòng và trở về điểm xuất phát.",
                    'location' => $location,
                    'start_time' => '08:00',
                    'end_time' => '18:00',
                    'meeting_point' => 'Sảnh khách sạn',
                    'activities' => "Tham quan điểm cuối cùng, mua sắm đặc sản {$location}, trả phòng khách sạn, di chuyển về điểm xuất phát",
                    'meals' => 'Ăn sáng tại khách sạn, ăn trưa tại nhà hàng địa phương',
                    'accommodation' => 'Không',
                    'transportation' => 'Xe du lịch về điểm xuất phát',
                    'notes' => 'Trả phòng khách sạn trước 12:00. Dự kiến về đến điểm xuất phát lúc 18:00.',
                ];
            } else {
                // Các ngày giữa: Tham quan chính
                $schedules[] = [
                    'tour_id' => $tour->id,
                    'day_number' => $day,
                    'title' => "Ngày {$day}: {$location} - Tham quan chính",
                    'description' => "Tham quan các điểm du lịch chính tại {$location}, trải nghiệm văn hóa địa phương và thưởng thức ẩm thực.",
                    'location' => $location,
                    'start_time' => '08:00',
                    'end_time' => '20:00',
                    'meeting_point' => 'Sảnh khách sạn',
                    'activities' => "Tham quan các điểm du lịch nổi tiếng tại {$location}, trải nghiệm văn hóa địa phương, chụp ảnh, thưởng thức ẩm thực",
                    'meals' => 'Ăn sáng tại khách sạn, ăn trưa tại nhà hàng địa phương, ăn tối tại nhà hàng',
                    'accommodation' => 'Khách sạn 3-4* tại trung tâm',
                    'transportation' => 'Xe du lịch 29-45 chỗ',
                    'notes' => 'Mang theo máy ảnh, giày đi bộ. Thời tiết có thể thay đổi, nên chuẩn bị áo khoác.',
                ];
            }
        }
        
        // Tạo lịch trình
        foreach ($schedules as $scheduleData) {
            TourSchedule::create($scheduleData);
        }
        
        $this->command->info("✓ Đã thêm {$days} ngày lịch trình cho tour: {$title}");
    }
}
