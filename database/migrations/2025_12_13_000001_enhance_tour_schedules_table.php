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
            // Thêm các trường chi tiết cho lịch trình từng ngày
            $table->string('location')->nullable()->after('title'); // Địa điểm chính trong ngày
            $table->time('start_time')->nullable()->after('location'); // Giờ bắt đầu hoạt động
            $table->time('end_time')->nullable()->after('start_time'); // Giờ kết thúc hoạt động
            $table->string('meeting_point')->nullable()->after('end_time'); // Điểm tập trung
            $table->text('activities')->nullable()->after('meeting_point'); // Các hoạt động trong ngày
            $table->text('meals')->nullable()->after('activities'); // Bữa ăn (sáng, trưa, tối)
            $table->string('accommodation')->nullable()->after('meals'); // Nơi nghỉ đêm
            $table->text('transportation')->nullable()->after('accommodation'); // Phương tiện di chuyển
            $table->text('notes')->nullable()->after('transportation'); // Ghi chú đặc biệt
            $table->json('images')->nullable()->after('notes'); // Hình ảnh minh họa
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tour_schedules', function (Blueprint $table) {
            $table->dropColumn([
                'location',
                'start_time',
                'end_time', 
                'meeting_point',
                'activities',
                'meals',
                'accommodation',
                'transportation',
                'notes',
                'images'
            ]);
        });
    }
};