<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Xóa cột 'role' cũ vì đã dùng bảng user_roles
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Khôi phục lại cột 'role' nếu rollback
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('customer')->after('phone');
            }
        });
    }
};
