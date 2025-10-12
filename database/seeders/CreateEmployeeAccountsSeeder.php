<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateEmployeeAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::all();
        
        foreach($employees as $employee) {
            if(!$employee->user) {
                $user = User::create([
                    'name' => $employee->name,
                    'email' => $employee->email,
                    'password' => Hash::make('123456'),
                ]);
                
                $employee->update(['user_id' => $user->id]);
                
                echo "Created account for: " . $employee->name . "\n";
            }
        }
    }
}
