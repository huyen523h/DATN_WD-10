<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            // RolePermissionSeeder::class, // Bỏ qua vì có lỗi
            CategorySeeder::class,
            GuideCategorySeeder::class,
            UserSeeder::class,
            TourSeeder::class,
            BannerSeeder::class,
            // PromotionSeeder::class, // Bỏ qua vì dữ liệu đã tồn tại
        ]);
    }
}
