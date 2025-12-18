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
        Schema::table('tours', function (Blueprint $table) {
            // Chú thích: Chỉ thêm cột NẾU nó chưa tồn tại. Đây là mấu chốt an toàn.
            if (!Schema::hasColumn('tours', 'departure_date')) {
                $table->date('departure_date')->nullable()->after('available_seats');
            }

            if (!Schema::hasColumn('tours', 'image')) {
                $table->string('image', 255)->nullable()->after('departure_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            // Chú thích: Khi rollback, cũng kiểm tra để tránh lỗi
            if (Schema::hasColumn('tours', 'departure_date')) {
                $table->dropColumn('departure_date');
            }
            if (Schema::hasColumn('tours', 'image')) {
                $table->dropColumn('image');
            }
        });
    }
};