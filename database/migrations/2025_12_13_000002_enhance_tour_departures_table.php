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
        Schema::table('tour_departures', function (Blueprint $table) {
            // Thêm thông tin chi tiết về giờ khởi hành và hướng dẫn viên
            $table->time('departure_time')->nullable()->after('departure_date'); // Giờ khởi hành cụ thể
            $table->string('departure_location')->nullable()->after('departure_time'); // Địa điểm khởi hành
            $table->text('departure_instructions')->nullable()->after('departure_location'); // Hướng dẫn tập trung
            
            // Thông tin hướng dẫn viên phụ trách (sử dụng bảng users thay vì guides)
            if (!Schema::hasColumn('tour_departures', 'guide_id')) {
                $table->foreignId('guide_id')->nullable()->constrained('users')->nullOnDelete()->after('departure_instructions');
            }
            
            // Thông tin hướng dẫn viên dự phòng
            $table->foreignId('backup_guide_id')->nullable()->constrained('users')->nullOnDelete()->after('guide_id');
            
            // Thông tin liên hệ khẩn cấp
            $table->string('emergency_contact')->nullable()->after('backup_guide_id');
            $table->string('emergency_phone')->nullable()->after('emergency_contact');
            
            // Ghi chú đặc biệt cho chuyến đi
            $table->text('special_notes')->nullable()->after('emergency_phone');
            
            // Trạng thái chuẩn bị
            $table->enum('preparation_status', ['pending', 'preparing', 'ready', 'departed', 'completed'])->default('pending')->after('special_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tour_departures', function (Blueprint $table) {
            if (Schema::hasColumn('tour_departures', 'backup_guide_id')) {
                $table->dropForeign(['backup_guide_id']);
            }
            if (Schema::hasColumn('tour_departures', 'guide_id')) {
                $table->dropForeign(['guide_id']);
            }
            
            $table->dropColumn([
                'departure_time',
                'departure_location',
                'departure_instructions',
                'backup_guide_id',
                'emergency_contact',
                'emergency_phone',
                'special_notes',
                'preparation_status'
            ]);
            
            if (Schema::hasColumn('tour_departures', 'guide_id')) {
                $table->dropColumn('guide_id');
            }
        });
    }
};