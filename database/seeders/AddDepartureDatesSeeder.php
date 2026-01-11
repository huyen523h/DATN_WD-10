<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tour;
use App\Models\TourDeparture;
use Carbon\Carbon;

class AddDepartureDatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        // Lấy tất cả các tour active
        $tours = Tour::where('status', 'active')->get();
        
        if ($tours->isEmpty()) {
            $this->command->warn('Không có tour nào trong database.');
            return;
        }
        
        $this->command->info("Đang kiểm tra và thêm ngày khởi hành cho {$tours->count()} tour...");
        
        $toursNeedingDates = [];
        $toursWithEnoughDates = [];
        
        foreach ($tours as $tour) {
            // Đếm số departure dates hiện có (chỉ tính các ngày trong tương lai)
            $departureCount = TourDeparture::where('tour_id', $tour->id)
                ->whereDate('departure_date', '>=', $now->toDateString())
                ->count();
            
            if ($departureCount < 3) {
                $toursNeedingDates[] = [
                    'tour' => $tour,
                    'current_count' => $departureCount,
                    'needed' => 3 - $departureCount
                ];
            } else {
                $toursWithEnoughDates[] = $tour;
            }
        }
        
        $this->command->info("Tìm thấy " . count($toursNeedingDates) . " tour cần thêm ngày khởi hành.");
        $this->command->info("Có " . count($toursWithEnoughDates) . " tour đã đủ 3 ngày khởi hành.");
        
        if (empty($toursNeedingDates)) {
            $this->command->info("Tất cả tour đã đủ 3 ngày khởi hành!");
            return;
        }
        
        // Bắt đầu từ ngày mai
        $startDate = $now->copy()->addDay();
        
        foreach ($toursNeedingDates as $item) {
            $tour = $item['tour'];
            $needed = $item['needed'];
            $currentCount = $item['current_count'];
            
            $this->command->info("\nĐang xử lý tour: {$tour->title}");
            $this->command->info("  - Hiện có: {$currentCount} ngày khởi hành");
            $this->command->info("  - Cần thêm: {$needed} ngày");
            
            // Lấy ngày khởi hành hiện có của tour (trong tương lai)
            $existingDates = TourDeparture::where('tour_id', $tour->id)
                ->whereDate('departure_date', '>=', $now->toDateString())
                ->orderBy('departure_date')
                ->pluck('departure_date')
                ->map(function($date) {
                    return Carbon::parse($date)->format('Y-m-d');
                })
                ->toArray();
            
            // Tìm ngày bắt đầu để thêm (sau ngày khởi hành cuối cùng hoặc từ ngày mai)
            $lastDate = !empty($existingDates) 
                ? Carbon::parse(end($existingDates)) 
                : $startDate->copy();
            
            $nextDate = $lastDate->copy()->addWeek(); // Bắt đầu từ 1 tuần sau ngày cuối
            
            // Thêm các ngày khởi hành còn thiếu
            for ($i = 0; $i < $needed; $i++) {
                // Tìm ngày chưa có departure
                while (in_array($nextDate->format('Y-m-d'), $existingDates)) {
                    $nextDate->addWeek();
                }
                
                // Kiểm tra xem ngày này đã có departure cho tour này chưa
                $existing = TourDeparture::where('tour_id', $tour->id)
                    ->whereDate('departure_date', $nextDate->toDateString())
                    ->first();
                
                if ($existing) {
                    $this->command->warn("  - Ngày {$nextDate->format('d/m/Y')} đã tồn tại, bỏ qua.");
                    $nextDate->addWeek();
                    continue;
                }
                
                // Lấy giá từ tour
                $price = $tour->price_adult ?? $tour->price ?? 5000000;
                $childPrice = $tour->price_child ?? ($price * 0.8);
                $seatsTotal = $tour->available_seats ?? 30;
                $seatsAvailable = $seatsTotal;
                
                // Tạo departure mới
                TourDeparture::create([
                    'tour_id' => $tour->id,
                    'departure_date' => $nextDate->toDateString(),
                    'departure_time' => '06:00',
                    'start_time' => '06:00',
                    'end_time' => '18:00',
                    'departure_location' => 'Văn phòng công ty - 123 Nguyễn Huệ, Q1, TP.HCM',
                    'seats_total' => $seatsTotal,
                    'seats_available' => $seatsAvailable,
                    'price' => $price,
                    'child_price' => $childPrice,
                    'status' => 'available',
                    'meeting_point' => 'Văn phòng công ty - 123 Nguyễn Huệ, Q1, TP.HCM',
                    'departure_instructions' => 'Quý khách có mặt trước 30 phút (05:30). Mang theo CMND/CCCD và đồ dùng cá nhân. Liên hệ HDV qua số điện thoại được cung cấp.',
                ]);
                
                $this->command->info("  ✓ Đã thêm ngày khởi hành: {$nextDate->format('d/m/Y')} lúc 06:00");
                
                // Thêm vào danh sách để tránh trùng lặp
                $existingDates[] = $nextDate->format('Y-m-d');
                
                // Ngày tiếp theo cách 1-2 tuần
                $nextDate->addWeeks(rand(1, 2));
            }
        }
        
        $this->command->info("\n✓ Hoàn thành! Đã thêm ngày khởi hành cho tất cả tour cần thiết.");
    }
}
