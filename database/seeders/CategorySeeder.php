<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Du lịch trong nước',
                'description' => 'Các tour du lịch trong nước Việt Nam',
            ],
            [
                'name' => 'Du lịch nước ngoài',
                'description' => 'Các tour du lịch quốc tế',
            ],
            [
                'name' => 'Du lịch nghỉ dưỡng',
                'description' => 'Các tour nghỉ dưỡng tại resort, bãi biển',
            ],
            [
                'name' => 'Du lịch khám phá',
                'description' => 'Các tour khám phá, trekking, adventure',
            ],
            [
                'name' => 'Du lịch văn hóa',
                'description' => 'Các tour tham quan di tích, văn hóa lịch sử',
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::updateOrCreate(
                ['name' => $categoryData['name']],
                $categoryData
            );
        }
    }
}
