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
        Schema::table('reviews', function (Blueprint $table) {
            // Thêm cột liên kết với Đơn hàng (Booking)
            // Lấy cột 'tour_id' từ ảnh 1 (image_92040a.jpg) làm mốc
            $table->unsignedBigInteger('booking_id')->nullable()->after('tour_id');
            
            // Thêm cột để Admin trả lời (liên kết cha-con)
            $table->unsignedBigInteger('parent_id')->nullable()->after('booking_id');

            // --- Thêm khóa ngoại (foreign key) ---
            
            // Liên kết booking_id với bảng bookings
            // Nếu xóa booking, review liên quan cũng bị xóa
            $table->foreign('booking_id')
                  ->references('id')
                  ->on('bookings')
                  ->onDelete('cascade');

            // Liên kết parent_id với chính bảng reviews
            // Nếu xóa review gốc, các trả lời của admin cũng bị xóa
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('reviews')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Phải xóa foreign key trước khi xóa cột
            $table->dropForeign(['booking_id']);
            $table->dropForeign(['parent_id']);
            
            $table->dropColumn(['booking_id', 'parent_id']);
        });
    }
};