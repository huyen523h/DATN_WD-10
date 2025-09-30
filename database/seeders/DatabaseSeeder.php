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
        $this->call(RoleSeeder::class);
        $adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'phone' => '0123456789',
            'address' => 'Đà Nẵng, Việt Nam',
        ]);

        $adminUser->assignRole('admin');
        $customerUser = User::factory()->create([
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'phone' => '0987654321',
            'address' => 'Đà Nẵng, Việt Nam',
        ]);

        $customerUser->assignRole('customer');

        $staffUser = User::factory()->create([
            'name' => 'Nhân viên Staff',
            'email' => 'staff@example.com',
            'phone' => '0369258417',
            'address' => 'Đà Nẵng, Việt Nam',
        ]);

        $staffUser->assignRole('staff');
    }
}
