<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'description' => 'Quản trị viên có quyền cao nhất',
            ],
            [
                'name' => 'staff',
                'description' => 'Nhân viên có quyền quản lý và hỗ trợ',
            ],
            [
                'name' => 'customer',
                'description' => 'Khách hàng sử dụng dịch vụ',
            ],
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }
    }
}
