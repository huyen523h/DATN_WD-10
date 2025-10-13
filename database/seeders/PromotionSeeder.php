<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promotion;
use Carbon\Carbon;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        $promotions = [
            [
                'code' => 'WELCOME10',
                'description' => 'Giảm 10% cho khách hàng mới',
                'discount_percent' => 10,
                'discount_amount' => null,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(30),
                'status' => 'active',
            ],
            [
                'code' => 'SUMMER20',
                'description' => 'Giảm 20% tour mùa hè',
                'discount_percent' => 20,
                'discount_amount' => null,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(60),
                'status' => 'active',
            ],
            [
                'code' => 'FIXED50K',
                'description' => 'Giảm 50,000 VND',
                'discount_percent' => null,
                'discount_amount' => 50000,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(15),
                'status' => 'active',
            ],
            [
                'code' => 'WINTER15',
                'description' => 'Giảm 15% tour mùa đông',
                'discount_percent' => 15,
                'discount_amount' => null,
                'start_date' => Carbon::now()->addDays(10),
                'end_date' => Carbon::now()->addDays(45),
                'status' => 'inactive',
            ],
            [
                'code' => 'VIP100K',
                'description' => 'Giảm 100,000 VND cho khách VIP',
                'discount_percent' => null,
                'discount_amount' => 100000,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(90),
                'status' => 'active',
            ],
        ];

        foreach ($promotions as $promotion) {
            Promotion::create($promotion);
        }
    }
}
