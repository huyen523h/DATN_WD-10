<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            // Xoá các cột cũ không còn dùng tới để tránh lỗi NOT NULL khi insert
            if (Schema::hasColumn('vehicles', 'code')) {
                $table->dropColumn('code');
            }
            if (Schema::hasColumn('vehicles', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('vehicles', 'capacity')) {
                $table->dropColumn('capacity');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            // Khôi phục lại sơ bộ nếu rollback (cho an toàn, cho phép null)
            if (!Schema::hasColumn('vehicles', 'code')) {
                $table->string('code')->nullable();
            }
            if (!Schema::hasColumn('vehicles', 'name')) {
                $table->string('name')->nullable();
            }
            if (!Schema::hasColumn('vehicles', 'capacity')) {
                $table->unsignedInteger('capacity')->nullable();
            }
        });
    }
};


