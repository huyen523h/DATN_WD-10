<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Banner;
use Carbon\Carbon;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            // Hero Banners
            [
                'title' => 'Khám phá thế giới cùng Tour365',
                'description' => 'Trải nghiệm những chuyến du lịch tuyệt vời với dịch vụ chuyên nghiệp, an toàn và giá cả hợp lý.',
                'image_url' => 'https://via.placeholder.com/1200x600/0EA5E9/ffffff?text=Hero+Banner+1',
                'link_url' => '/tours',
                'type' => 'hero',
                'position' => 'top',
                'sort_order' => 1,
                'is_active' => true,
                'start_date' => now(),
                'end_date' => now()->addMonths(3),
                'target_audience' => ['all'],
                'click_count' => 0,
                'view_count' => 0,
            ],
            [
                'title' => 'Ưu đãi mùa hè 2025',
                'description' => 'Giảm đến 30% cho các tour biển và miền núi. Đặt ngay để không bỏ lỡ cơ hội!',
                'image_url' => 'https://via.placeholder.com/1200x600/06B6D4/ffffff?text=Summer+Promotion',
                'link_url' => '/promotions',
                'type' => 'hero',
                'position' => 'top',
                'sort_order' => 2,
                'is_active' => true,
                'start_date' => now(),
                'end_date' => now()->addMonths(2),
                'target_audience' => ['all'],
                'click_count' => 0,
                'view_count' => 0,
            ],

            // Promotion Banners
            [
                'title' => 'Tour Phú Quốc - Giảm 20%',
                'description' => 'Khám phá đảo ngọc xinh đẹp với giá ưu đãi',
                'image_url' => 'https://via.placeholder.com/400x300/FF6B6B/ffffff?text=Phu+Quoc+Tour',
                'link_url' => '/tours?search=phú+quốc',
                'type' => 'promotion',
                'position' => 'middle',
                'sort_order' => 1,
                'is_active' => true,
                'start_date' => now(),
                'end_date' => now()->addMonth(),
                'target_audience' => ['all'],
                'click_count' => 0,
                'view_count' => 0,
            ],
            [
                'title' => 'Tour Sapa - Mùa lúa chín',
                'description' => 'Ngắm nhìn ruộng bậc thang vàng óng tuyệt đẹp',
                'image_url' => 'https://via.placeholder.com/400x300/4ECDC4/ffffff?text=Sapa+Tour',
                'link_url' => '/tours?search=sapa',
                'type' => 'promotion',
                'position' => 'middle',
                'sort_order' => 2,
                'is_active' => true,
                'start_date' => now(),
                'end_date' => now()->addMonths(2),
                'target_audience' => ['all'],
                'click_count' => 0,
                'view_count' => 0,
            ],

            // Category Banners
            [
                'title' => 'Du lịch biển',
                'description' => 'Khám phá những bãi biển đẹp nhất Việt Nam',
                'image_url' => 'https://via.placeholder.com/300x200/38BDF8/ffffff?text=Beach+Tours',
                'link_url' => '/tours?category_id=1',
                'type' => 'category',
                'position' => 'sidebar',
                'sort_order' => 1,
                'is_active' => true,
                'start_date' => now(),
                'end_date' => null,
                'target_audience' => ['all'],
                'click_count' => 0,
                'view_count' => 0,
            ],
            [
                'title' => 'Du lịch miền núi',
                'description' => 'Trải nghiệm thiên nhiên hoang sơ và khí hậu mát mẻ',
                'image_url' => 'https://via.placeholder.com/300x200/10B981/ffffff?text=Mountain+Tours',
                'link_url' => '/tours?category_id=2',
                'type' => 'category',
                'position' => 'sidebar',
                'sort_order' => 2,
                'is_active' => true,
                'start_date' => now(),
                'end_date' => null,
                'target_audience' => ['all'],
                'click_count' => 0,
                'view_count' => 0,
            ],

            // Featured Banners
            [
                'title' => 'Tour nổi bật tuần này',
                'description' => 'Đà Nẵng - Hội An 3 ngày 2 đêm',
                'image_url' => 'https://via.placeholder.com/500x300/F59E0B/ffffff?text=Featured+Tour',
                'link_url' => '/tours?featured=1',
                'type' => 'featured',
                'position' => 'middle',
                'sort_order' => 1,
                'is_active' => true,
                'start_date' => now(),
                'end_date' => now()->addWeek(),
                'target_audience' => ['all'],
                'click_count' => 0,
                'view_count' => 0,
            ],

            // Inactive Banner (for testing)
            [
                'title' => 'Banner không hoạt động',
                'description' => 'Banner này đã hết hạn hoặc bị tắt',
                'image_url' => 'https://via.placeholder.com/400x200/6B7280/ffffff?text=Inactive+Banner',
                'link_url' => null,
                'type' => 'promotion',
                'position' => 'bottom',
                'sort_order' => 99,
                'is_active' => false,
                'start_date' => now()->subMonth(),
                'end_date' => now()->subWeek(),
                'target_audience' => ['all'],
                'click_count' => 0,
                'view_count' => 0,
            ],

            // Banner for new users
            [
                'title' => 'Chào mừng thành viên mới',
                'description' => 'Nhận ngay voucher 500k cho đơn hàng đầu tiên',
                'image_url' => 'https://via.placeholder.com/400x200/8B5CF6/ffffff?text=New+User+Welcome',
                'link_url' => '/register',
                'type' => 'promotion',
                'position' => 'top',
                'sort_order' => 3,
                'is_active' => true,
                'start_date' => now(),
                'end_date' => now()->addMonths(6),
                'target_audience' => ['new_users'],
                'click_count' => 0,
                'view_count' => 0,
            ],

            // Banner for returning users
            [
                'title' => 'Cảm ơn bạn đã quay lại',
                'description' => 'Ưu đãi đặc biệt cho khách hàng thân thiết',
                'image_url' => 'https://via.placeholder.com/400x200/EC4899/ffffff?text=Returning+User',
                'link_url' => '/promotions?type=loyalty',
                'type' => 'promotion',
                'position' => 'middle',
                'sort_order' => 3,
                'is_active' => true,
                'start_date' => now(),
                'end_date' => now()->addMonths(3),
                'target_audience' => ['returning_users'],
                'click_count' => 0,
                'view_count' => 0,
            ],
        ];

        foreach ($banners as $bannerData) {
            Banner::create($bannerData);
        }

        $this->command->info('Banner seeder completed successfully!');
        $this->command->info('Created ' . count($banners) . ' banners');
    }
}
