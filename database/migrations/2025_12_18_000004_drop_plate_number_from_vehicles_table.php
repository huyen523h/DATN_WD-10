<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            // Cột cũ plate_number không còn dùng nữa → xoá để tránh lỗi NOT NULL
            if (Schema::hasColumn('vehicles', 'plate_number')) {
                $table->dropColumn('plate_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            // Khi rollback, thêm lại plate_number (cho phép null để không gây lỗi)
            if (!Schema::hasColumn('vehicles', 'plate_number')) {
                $table->string('plate_number')->nullable()->after('id');
            }
        });
    }
};


