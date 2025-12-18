<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Bước 1: Chuyển đổi dữ liệu cũ từ string text sang string số trước
        DB::statement("UPDATE vehicles SET status = CASE 
            WHEN status = 'available' THEN '1'
            WHEN status = 'maintenance' THEN '2'
            WHEN status = 'inactive' THEN '0'
            WHEN status = '1' THEN '1'
            WHEN status = '2' THEN '2'
            WHEN status = '0' THEN '0'
            ELSE '0'
        END");

        // Bước 2: Đổi kiểu cột từ string sang tinyInteger bằng raw SQL
        DB::statement("ALTER TABLE vehicles MODIFY COLUMN status TINYINT NOT NULL DEFAULT 1");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Chuyển đổi dữ liệu từ integer sang string trước khi đổi kiểu cột
        DB::statement("UPDATE vehicles SET status = CASE 
            WHEN status = 1 THEN 'available'
            WHEN status = 2 THEN 'maintenance'
            WHEN status = 0 THEN 'inactive'
            ELSE 'inactive'
        END");

        // Đổi kiểu cột từ tinyInteger sang string bằng raw SQL
        DB::statement("ALTER TABLE vehicles MODIFY COLUMN status VARCHAR(255) NOT NULL DEFAULT 'available'");
    }
};

