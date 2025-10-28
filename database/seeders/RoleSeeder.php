<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

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
                'description' => 'Quản trị viên hệ thống',
            ],
            [
                'name' => 'staff',
                'description' => 'Nhân viên công ty',
            ],
            [
                'name' => 'customer',
                'description' => 'Khách hàng',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
