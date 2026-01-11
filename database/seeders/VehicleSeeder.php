<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = [
            [
                'license_plate' => '51G-789.12',
                'vehicle_type' => '29',
                'brand' => 'Toyota',
                'year' => 2022,
                'color' => 'Trắng',
                'status' => 1,
                'driver_name' => 'Nguyễn Văn A',
                'driver_phone' => '0901234567',
                'notes' => 'Xe mới, đang hoạt động tốt',
            ],
            [
                'license_plate' => '30A-456.78',
                'vehicle_type' => '29',
                'brand' => 'Ford',
                'year' => 2021,
                'color' => 'Xanh',
                'status' => 1,
                'driver_name' => 'Trần Văn B',
                'driver_phone' => '0902345678',
                'notes' => 'Xe đã sử dụng 2 năm',
            ],
            [
                'license_plate' => '43B-234.56',
                'vehicle_type' => '45',
                'brand' => 'Mercedes',
                'year' => 2023,
                'color' => 'Đen',
                'status' => 1,
                'driver_name' => 'Lê Văn C',
                'driver_phone' => '0903456789',
                'notes' => 'Xe cao cấp, phục vụ tour VIP',
            ],
            [
                'license_plate' => '75A-123.45',
                'vehicle_type' => '16',
                'brand' => 'Hyundai',
                'year' => 2022,
                'color' => 'Bạc',
                'status' => 1,
                'driver_name' => 'Phạm Văn D',
                'driver_phone' => '0904567890',
                'notes' => 'Xe nhỏ gọn, phù hợp tour nhóm nhỏ',
            ],
            [
                'license_plate' => '92C-567.89',
                'vehicle_type' => '29',
                'brand' => 'Isuzu',
                'year' => 2021,
                'color' => 'Trắng',
                'status' => 1,
                'driver_name' => 'Hoàng Văn E',
                'driver_phone' => '0905678901',
                'notes' => 'Xe bền, tiết kiệm nhiên liệu',
            ],
        ];

        foreach ($vehicles as $vehicleData) {
            // Kiểm tra xem xe đã tồn tại chưa (theo biển số)
            $existing = Vehicle::where('license_plate', $vehicleData['license_plate'])->first();
            
            if (!$existing) {
                Vehicle::create($vehicleData);
                $this->command->info("Đã thêm xe: {$vehicleData['license_plate']}");
            } else {
                $this->command->warn("Xe {$vehicleData['license_plate']} đã tồn tại, bỏ qua.");
            }
        }

        $this->command->info('Hoàn thành thêm 5 xe vào hệ thống!');
    }
}
