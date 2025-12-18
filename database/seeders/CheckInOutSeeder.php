<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CheckInOut;
use App\Models\User;
use App\Models\Booking;
use Carbon\Carbon;

class CheckInOutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy các user và booking để tạo dữ liệu
        $users = User::whereHas('roles', function($query) {
            $query->where('name', 'customer');
        })->get();

        $bookings = Booking::where('status', 'confirmed')->get();

        if ($users->isEmpty() || $bookings->isEmpty()) {
            $this->command->warn('Không tìm thấy user hoặc booking nào! Vui lòng chạy UserSeeder và tạo booking trước.');
            return;
        }

        // Reset dữ liệu cũ nếu cần
        // CheckInOut::truncate();

        $samples = [
            [
                'user_id' => $users->random()->id,
                'booking_id' => $bookings->random()->id,
                'type' => 'check_in',
                'check_time' => Carbon::now()->subDays(2)->setTime(7, 30, 0),
                'location' => 'Bến xe Miền Tây, Q. Bình Tân, TP.HCM',
                'latitude' => 10.7530,
                'longitude' => 106.6274,
                'notes' => 'Khách hàng đến đúng giờ, ổn định',
                'status' => 'confirmed',
                'verified_by' => 'Nhân viên Nguyễn Văn A',
                'verified_at' => Carbon::now()->subDays(2)->setTime(7, 35, 0),
                'metadata' => [
                    'device' => 'Mobile',
                    'ip_address' => '192.168.1.100',
                    'photos' => ['photo1.jpg', 'photo2.jpg']
                ]
            ],
            [
                'user_id' => $users->random()->id,
                'booking_id' => $bookings->random()->id,
                'type' => 'check_out',
                'check_time' => Carbon::now()->subDays(1)->setTime(18, 45, 0),
                'location' => 'Khách sạn Pullman Saigon, Q. 1, TP.HCM',
                'latitude' => 10.7837,
                'longitude' => 106.7018,
                'notes' => 'Tour kết thúc tốt đẹp, khách hàng hài lòng',
                'status' => 'confirmed',
                'verified_by' => 'Hướng dẫn viên Trần Thị B',
                'verified_at' => Carbon::now()->subDays(1)->setTime(18, 50, 0),
                'metadata' => [
                    'device' => 'Tablet',
                    'ip_address' => '192.168.1.101',
                    'feedback' => 'Excellent service'
                ]
            ],
            [
                'user_id' => $users->random()->id,
                'booking_id' => $bookings->random()->id,
                'type' => 'check_in',
                'check_time' => Carbon::now()->subHours(12),
                'location' => 'Sân bay Nội Bài, Hà Nội',
                'latitude' => 21.2210,
                'longitude' => 105.8066,
                'notes' => 'Check-in tại sân bay',
                'status' => 'pending',
                'verified_by' => null,
                'verified_at' => null,
                'metadata' => [
                    'device' => 'Mobile',
                    'ip_address' => '10.0.0.50',
                ]
            ],
            [
                'user_id' => $users->random()->id,
                'booking_id' => $bookings->random()->id,
                'type' => 'check_out',
                'check_time' => Carbon::now()->subHours(3),
                'location' => 'Bãi biển Mỹ Khê, Đà Nẵng',
                'latitude' => 16.0597,
                'longitude' => 108.2456,
                'notes' => 'Check-out tại điểm cuối tour',
                'status' => 'pending',
                'verified_by' => null,
                'verified_at' => null,
                'metadata' => [
                    'device' => 'Mobile',
                    'ip_address' => '172.16.0.10',
                    'weather' => 'Sunny, 28°C'
                ]
            ],
        ];

        foreach ($samples as $sample) {
            CheckInOut::create($sample);
        }

        $this->command->info('Đã tạo thành công ' . count($samples) . ' bản ghi check-in/check-out mẫu!');
    }
}

