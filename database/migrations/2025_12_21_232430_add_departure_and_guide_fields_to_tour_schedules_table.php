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
        Schema::table('tour_schedules', function (Blueprint $table) {
            // Thêm trường departure_id để liên kết với lịch khởi hành cụ thể
            if (!Schema::hasColumn('tour_schedules', 'departure_id')) {
                $table->foreignId('departure_id')
                    ->nullable()
                    ->after('tour_id')
                    ->constrained('tour_departures')
                    ->nullOnDelete();
            }
            
            // Thêm trường guide_id để chỉ định HDV phụ trách cho ngày này
            if (!Schema::hasColumn('tour_schedules', 'guide_id')) {
                $table->foreignId('guide_id')
                    ->nullable()
                    ->after('departure_id')
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tour_schedules', function (Blueprint $table) {
            if (Schema::hasColumn('tour_schedules', 'guide_id')) {
                $table->dropForeign(['guide_id']);
                $table->dropColumn('guide_id');
            }
            if (Schema::hasColumn('tour_schedules', 'departure_id')) {
                $table->dropForeign(['departure_id']);
                $table->dropColumn('departure_id');
            }
        });
    }
};
