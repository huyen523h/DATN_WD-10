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
            // Kiểm tra trước khi thêm, tránh lỗi trùng cột
            if (!Schema::hasColumn('tours', 'departure_date')) {
                $table->date('departure_date')->nullable()->after('available_seats');
            }

            if (!Schema::hasColumn('tours', 'image')) {
                $table->string('image')->nullable()->after('departure_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            if (Schema::hasColumn('tours', 'departure_date')) {
                $table->dropColumn('departure_date');
            }
            if (Schema::hasColumn('tours', 'image')) {
                $table->dropColumn('image');
            }
        });
    }
};
