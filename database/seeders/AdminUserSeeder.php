<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if not exists
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '0123456789',
                'address' => 'Admin Address',
                'is_active' => true,
            ]);
        }

        // Create staff user if not exists
        if (!User::where('email', 'staff@example.com')->exists()) {
            User::create([
                'name' => 'Staff User',
                'email' => 'staff@example.com',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'phone' => '0987654321',
                'address' => 'Staff Address',
                'is_active' => true,
            ]);
        }

        // Create customer user if not exists
        if (!User::where('email', 'customer@example.com')->exists()) {
            User::create([
                'name' => 'Customer User',
                'email' => 'customer@example.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '0555555555',
                'address' => 'Customer Address',
                'is_active' => true,
            ]);
        }
    }
}
