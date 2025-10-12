<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Role;
use Carbon\Carbon;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $adminRole = Role::where('name', 'admin')->first();
        $managerRole = Role::where('name', 'manager')->first();
        $employeeRole = Role::where('name', 'employee')->first();

        $employees = [
            [
                'employee_code' => 'EMP001',
                'name' => 'Nguyễn Văn An',
                'email' => 'an.nguyen@tour365.vn',
                'phone' => '0901234567',
                'position' => 'Giám đốc',
                'department' => 'IT',
                'hire_date' => Carbon::now()->subYears(5),
                'salary' => 50000000,
                'status' => 'active',
                'address' => '123 Đường ABC, Quận 1, TP.HCM',
                'role_id' => $adminRole->id ?? 1,
            ],
            [
                'employee_code' => 'EMP002',
                'name' => 'Trần Thị Bình',
                'email' => 'binh.tran@tour365.vn',
                'phone' => '0901234568',
                'position' => 'Trưởng phòng Marketing',
                'department' => 'Marketing',
                'hire_date' => Carbon::now()->subYears(3),
                'salary' => 25000000,
                'status' => 'active',
                'address' => '456 Đường DEF, Quận 2, TP.HCM',
                'role_id' => $managerRole->id ?? 2,
            ],
            [
                'employee_code' => 'EMP003',
                'name' => 'Lê Văn Cường',
                'email' => 'cuong.le@tour365.vn',
                'phone' => '0901234569',
                'position' => 'Nhân viên kinh doanh',
                'department' => 'Sales',
                'hire_date' => Carbon::now()->subYears(2),
                'salary' => 15000000,
                'status' => 'active',
                'address' => '789 Đường GHI, Quận 3, TP.HCM',
                'role_id' => $employeeRole->id ?? 3,
            ],
            [
                'employee_code' => 'EMP004',
                'name' => 'Phạm Thị Dung',
                'email' => 'dung.pham@tour365.vn',
                'phone' => '0901234570',
                'position' => 'Chuyên viên IT',
                'department' => 'IT',
                'hire_date' => Carbon::now()->subYear(),
                'salary' => 18000000,
                'status' => 'active',
                'address' => '321 Đường JKL, Quận 4, TP.HCM',
                'role_id' => $employeeRole->id ?? 3,
            ],
            [
                'employee_code' => 'EMP005',
                'name' => 'Hoàng Văn Em',
                'email' => 'em.hoang@tour365.vn',
                'phone' => '0901234571',
                'position' => 'Nhân viên tài chính',
                'department' => 'Finance',
                'hire_date' => Carbon::now()->subMonths(6),
                'salary' => 12000000,
                'status' => 'inactive',
                'address' => '654 Đường MNO, Quận 5, TP.HCM',
                'role_id' => $employeeRole->id ?? 3,
            ],
        ];

        foreach ($employees as $employeeData) {
            Employee::create($employeeData);
        }
    }
}
