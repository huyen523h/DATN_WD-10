<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tour;
use App\Models\TourDeparture;
use Carbon\Carbon;

class TourDepartureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy ngày 14 của tháng hiện tại hoặc tháng tới (nếu ngày 14 đã qua)
        $now = Carbon::now();
        $day14 = Carbon::create($now->year, $now->month, 14);
        
        // Nếu ngày 14 đã qua trong tháng này, dùng ngày 14 tháng sau
        if ($day14->isPast()) {
            $day14 = $day14->addMonth();
        }
        
        // Lấy 3 tour đầu tiên có sẵn
        $tours = Tour::where('status', 'active')
            ->orderBy('id')
            ->limit(3)
            ->get();
        
        if ($tours->isEmpty()) {
            $this->command->warn('Không có tour nào trong database. Vui lòng tạo tour trước.');
            return;
        }
        
        $this->command->info("Thêm ngày khởi hành vào ngày {$day14->format('d/m/Y')} cho {$tours->count()} tour...");
        
        foreach ($tours as $index => $tour) {
            // Kiểm tra xem đã có departure vào ngày 14 chưa
            $existing = TourDeparture::where('tour_id', $tour->id)
                ->whereDate('departure_date', $day14->toDateString())
                ->first();
            
            if ($existing) {
                $this->command->warn("Tour '{$tour->title}' đã có ngày khởi hành vào {$day14->format('d/m/Y')}, bỏ qua.");
                continue;
            }
            
            // Tạo departure mới
            $seatsTotal = $tour->available_seats ?? 30; // Mặc định 30 chỗ nếu không có
            $seatsAvailable = $seatsTotal;
            
            $price = $tour->price_adult ?? $tour->price ?? 5000000;
            $childPrice = $tour->price_child ?? ($price * 0.8);
            
            TourDeparture::create([
                'tour_id' => $tour->id,
                'departure_date' => $day14->toDateString(),
                'departure_time' => '06:00',
                'departure_location' => 'Văn phòng công ty - 123 Nguyễn Huệ, Q1, TP.HCM',
                'seats_total' => $seatsTotal,
                'seats_available' => $seatsAvailable,
                'price' => $price,
                'child_price' => $childPrice,
                'status' => 'available',
                'meeting_point' => 'Văn phòng công ty - 123 Nguyễn Huệ, Q1, TP.HCM',
                'departure_instructions' => 'Quý khách có mặt trước 30 phút (05:30). Mang theo CMND/CCCD và đồ dùng cá nhân. Liên hệ HDV qua số điện thoại được cung cấp.',
            ]);
            
            $this->command->info("✓ Đã thêm ngày khởi hành cho tour: {$tour->title} vào {$day14->format('d/m/Y')} lúc 06:00");
        }
        
        $this->command->info("Hoàn thành! Đã thêm ngày khởi hành vào {$day14->format('d/m/Y')} cho {$tours->count()} tour.");
    }
}
