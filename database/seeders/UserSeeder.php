<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Role::all();
        
        // Tạo admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@tour365.vn'],
            [
                'name' => 'Admin Tour365',
                'password' => Hash::make('password'),
                'phone' => '0901234567',
                'address' => '123 Đường ABC, Quận 1, TP.HCM',
            ]
        );
        if (!$admin->roles()->where('name', 'admin')->exists()) {
            $admin->roles()->attach($roles->where('name', 'admin')->first()->id);
        }

        // Tạo staff user
        $staff = User::firstOrCreate(
            ['email' => 'staff@tour365.vn'],
            [
                'name' => 'Nhân viên Tour365',
                'password' => Hash::make('password'),
                'phone' => '0901234568',
                'address' => '456 Đường DEF, Quận 2, TP.HCM',
            ]
        );
        if (!$staff->roles()->where('name', 'staff')->exists()) {
            $staff->roles()->attach($roles->where('name', 'staff')->first()->id);
        }

        // Tạo customer users
        $customers = [
            [
                'name' => 'Nguyễn Văn An',
                'email' => 'an.nguyen@email.com',
                'phone' => '0901234569',
                'address' => '789 Đường GHI, Quận 3, TP.HCM',
            ],
            [
                'name' => 'Trần Thị Bình',
                'email' => 'binh.tran@email.com',
                'phone' => '0901234570',
                'address' => '321 Đường JKL, Quận 4, TP.HCM',
            ],
            [
                'name' => 'Lê Văn Cường',
                'email' => 'cuong.le@email.com',
                'phone' => '0901234571',
                'address' => '654 Đường MNO, Quận 5, TP.HCM',
            ],
        ];

        foreach ($customers as $customerData) {
            $customer = User::firstOrCreate(
                ['email' => $customerData['email']],
                array_merge($customerData, [
                    'password' => Hash::make('password'),
                ])
            );
            if (!$customer->roles()->where('name', 'customer')->exists()) {
                $customer->roles()->attach($roles->where('name', 'customer')->first()->id);
            }
        }
    }
}
