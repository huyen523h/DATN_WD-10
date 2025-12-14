<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ShowAdminInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hiển thị thông tin tài khoản admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $admin = User::whereHas('roles', function($query) {
            $query->where('name', 'admin');
        })->first();

        if ($admin) {
            $this->info('=== THÔNG TIN TÀI KHOẢN ADMIN ===');
            $this->line('Email: ' . $admin->email);
            $this->line('Tên: ' . $admin->name);
            $this->line('Số điện thoại: ' . $admin->phone);
            $this->line('Địa chỉ: ' . $admin->address);
            $this->line('Mật khẩu: password');
            $this->info('================================');
        } else {
            $this->error('Không tìm thấy tài khoản admin!');
        }
    }
}
