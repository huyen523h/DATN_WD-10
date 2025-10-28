<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

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
                'name' => 'Du lịch biển đảo',
                'description' => 'Các tour du lịch biển và đảo',
            ],
            [
                'name' => 'Du lịch văn hóa',
                'description' => 'Các tour du lịch văn hóa và lịch sử',
            ],
            [
                'name' => 'Du lịch thiên nhiên',
                'description' => 'Các tour du lịch khám phá thiên nhiên',
            ],
            [
                'name' => 'Du lịch tâm linh',
                'description' => 'Các tour du lịch tâm linh và tôn giáo',
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
