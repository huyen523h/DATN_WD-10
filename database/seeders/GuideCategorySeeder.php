<?php

namespace Database\Seeders;

use App\Models\GuideCategory;
use Illuminate\Database\Seeder;

class GuideCategorySeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['name' => 'Nội địa - Miền Bắc', 'slug' => 'noi-dia-mien-bac', 'type' => 'region'],
            ['name' => 'Nội địa - Miền Trung', 'slug' => 'noi-dia-mien-trung', 'type' => 'region'],
            ['name' => 'Nội địa - Miền Nam', 'slug' => 'noi-dia-mien-nam', 'type' => 'region'],
            ['name' => 'Chuyên khách đoàn', 'slug' => 'chuyen-khach-doan', 'type' => 'segment'],
            ['name' => 'Chuyên khách lẻ', 'slug' => 'chuyen-khach-le', 'type' => 'segment'],
            ['name' => 'Chuyên tour ẩm thực', 'slug' => 'chuyen-tour-am-thuc', 'type' => 'specialty'],
        ];

        foreach ($defaults as $data) {
            GuideCategory::firstOrCreate(
                ['slug' => $data['slug']],
                $data + ['is_default' => false, 'is_active' => true],
            );
        }
    }
}


