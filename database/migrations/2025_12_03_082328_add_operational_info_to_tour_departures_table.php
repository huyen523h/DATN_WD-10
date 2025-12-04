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
        // 1. Thông tin Hướng dẫn viên (Liên kết với bảng users)
        // nullable() vì lúc mới tạo lịch chưa chắc đã có HDV ngay
        $table->foreignId('guide_id')->nullable()->constrained('users')->nullOnDelete();

        // 2. Thông tin Xe & Tài xế
        $table->string('vehicle_details')->nullable(); // VD: "Xe 45 chỗ - 29B-123.45"
        $table->string('driver_contact')->nullable();  // VD: "Tài xế Hùng - 0909..."

        // 3. File Lịch trình chi tiết / Hợp đồng
        // Lưu đường dẫn file PDF/Word để khách tải về
        $table->string('itinerary_file')->nullable(); 
    });
}

public function down(): void
{
    Schema::table('tour_departures', function (Blueprint $table) {
        $table->dropForeign(['guide_id']);
        $table->dropColumn(['guide_id', 'vehicle_details', 'driver_contact', 'itinerary_file']);
    });
}

};
