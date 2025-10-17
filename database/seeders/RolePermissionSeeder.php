<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo permissions
        $permissions = [
            // Tour permissions
            ['name' => 'tours.view', 'display_name' => 'Xem tour', 'description' => 'Xem danh sách tour', 'module' => 'tours'],
            ['name' => 'tours.create', 'display_name' => 'Tạo tour', 'description' => 'Tạo tour mới', 'module' => 'tours'],
            ['name' => 'tours.edit', 'display_name' => 'Chỉnh sửa tour', 'description' => 'Chỉnh sửa thông tin tour', 'module' => 'tours'],
            ['name' => 'tours.delete', 'display_name' => 'Xóa tour', 'description' => 'Xóa tour', 'module' => 'tours'],
            
            // Booking permissions
            ['name' => 'bookings.view', 'display_name' => 'Xem đặt tour', 'description' => 'Xem danh sách đặt tour', 'module' => 'bookings'],
            ['name' => 'bookings.create', 'display_name' => 'Tạo đặt tour', 'description' => 'Tạo đặt tour mới', 'module' => 'bookings'],
            ['name' => 'bookings.edit', 'display_name' => 'Chỉnh sửa đặt tour', 'description' => 'Chỉnh sửa thông tin đặt tour', 'module' => 'bookings'],
            ['name' => 'bookings.delete', 'display_name' => 'Xóa đặt tour', 'description' => 'Xóa đặt tour', 'module' => 'bookings'],
            
            // Customer permissions
            ['name' => 'customers.view', 'display_name' => 'Xem khách hàng', 'description' => 'Xem danh sách khách hàng', 'module' => 'customers'],
            ['name' => 'customers.create', 'display_name' => 'Tạo khách hàng', 'description' => 'Tạo tài khoản khách hàng mới', 'module' => 'customers'],
            ['name' => 'customers.edit', 'display_name' => 'Chỉnh sửa khách hàng', 'description' => 'Chỉnh sửa thông tin khách hàng', 'module' => 'customers'],
            ['name' => 'customers.delete', 'display_name' => 'Xóa khách hàng', 'description' => 'Xóa tài khoản khách hàng', 'module' => 'customers'],
            
            // Employee permissions
            ['name' => 'employees.view', 'display_name' => 'Xem nhân viên', 'description' => 'Xem danh sách nhân viên', 'module' => 'employees'],
            ['name' => 'employees.create', 'display_name' => 'Tạo nhân viên', 'description' => 'Tạo tài khoản nhân viên mới', 'module' => 'employees'],
            ['name' => 'employees.edit', 'display_name' => 'Chỉnh sửa nhân viên', 'description' => 'Chỉnh sửa thông tin nhân viên', 'module' => 'employees'],
            ['name' => 'employees.delete', 'display_name' => 'Xóa nhân viên', 'description' => 'Xóa tài khoản nhân viên', 'module' => 'employees'],
            
            // Category permissions
            ['name' => 'categories.view', 'display_name' => 'Xem danh mục', 'description' => 'Xem danh sách danh mục', 'module' => 'categories'],
            ['name' => 'categories.create', 'display_name' => 'Tạo danh mục', 'description' => 'Tạo danh mục mới', 'module' => 'categories'],
            ['name' => 'categories.edit', 'display_name' => 'Chỉnh sửa danh mục', 'description' => 'Chỉnh sửa thông tin danh mục', 'module' => 'categories'],
            ['name' => 'categories.delete', 'display_name' => 'Xóa danh mục', 'description' => 'Xóa danh mục', 'module' => 'categories'],
            
            // Promotion permissions
            ['name' => 'promotions.view', 'display_name' => 'Xem khuyến mãi', 'description' => 'Xem danh sách khuyến mãi', 'module' => 'promotions'],
            ['name' => 'promotions.create', 'display_name' => 'Tạo khuyến mãi', 'description' => 'Tạo khuyến mãi mới', 'module' => 'promotions'],
            ['name' => 'promotions.edit', 'display_name' => 'Chỉnh sửa khuyến mãi', 'description' => 'Chỉnh sửa thông tin khuyến mãi', 'module' => 'promotions'],
            ['name' => 'promotions.delete', 'display_name' => 'Xóa khuyến mãi', 'description' => 'Xóa khuyến mãi', 'module' => 'promotions'],
            
            // Report permissions
            ['name' => 'reports.view', 'display_name' => 'Xem báo cáo', 'description' => 'Xem các báo cáo', 'module' => 'reports'],
            ['name' => 'reports.export', 'display_name' => 'Xuất báo cáo', 'description' => 'Xuất báo cáo ra file', 'module' => 'reports'],
            
            // Settings permissions
            ['name' => 'settings.view', 'display_name' => 'Xem cài đặt', 'description' => 'Xem cài đặt hệ thống', 'module' => 'settings'],
            ['name' => 'settings.edit', 'display_name' => 'Chỉnh sửa cài đặt', 'description' => 'Chỉnh sửa cài đặt hệ thống', 'module' => 'settings'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        // Tạo roles
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Quản trị viên',
                'description' => 'Có toàn quyền truy cập hệ thống',
                'is_active' => true,
                'permissions' => Permission::all()->pluck('name')->toArray()
            ],
            [
                'name' => 'staff',
                'display_name' => 'Nhân viên',
                'description' => 'Nhân viên có quyền hạn cơ bản',
                'is_active' => true,
                'permissions' => [
                    'tours.view', 'tours.create', 'tours.edit',
                    'bookings.view', 'bookings.create', 'bookings.edit',
                    'customers.view', 'customers.create', 'customers.edit',
                    'categories.view', 'categories.create', 'categories.edit',
                    'promotions.view', 'promotions.create', 'promotions.edit',
                    'reports.view'
                ]
            ],
            [
                'name' => 'customer',
                'display_name' => 'Khách hàng',
                'description' => 'Khách hàng sử dụng dịch vụ',
                'is_active' => true,
                'permissions' => [
                    'tours.view',
                    'bookings.view', 'bookings.create'
                ]
            ]
        ];

        foreach ($roles as $roleData) {
            $permissions = $roleData['permissions'];
            unset($roleData['permissions']);
            
            $role = Role::firstOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
            
            // Gán permissions cho role
            $permissionModels = Permission::whereIn('name', $permissions)->get();
            $role->permissions()->sync($permissionModels);
        }

        // Gán role cho user admin mặc định
        $adminUser = User::where('email', 'admin@tour365.com')->first();
        if ($adminUser) {
            $adminRole = Role::where('name', 'admin')->first();
            if ($adminRole && !$adminUser->roles()->where('role_id', $adminRole->id)->exists()) {
                $adminUser->roles()->attach($adminRole->id);
            }
        }

        // Gán role customer cho các user khác
        $customerRole = Role::where('name', 'customer')->first();
        if ($customerRole) {
            $users = User::where('email', '!=', 'admin@tour365.com')->get();
            foreach ($users as $user) {
                if (!$user->roles()->where('role_id', $customerRole->id)->exists()) {
                    $user->roles()->attach($customerRole->id);
                }
            }
        }
    }
}
