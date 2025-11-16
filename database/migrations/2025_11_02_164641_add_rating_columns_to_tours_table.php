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
            // Thêm cột điểm trung bình (ví dụ: 4.50)
            // Lấy cột 'status' từ ảnh 2 (image_920785.jpg) làm mốc
            $table->decimal('avg_rating', 3, 2)->default(0.00)->after('status');
            
            // Thêm cột đếm số lượt review
            $table->unsignedInteger('reviews_count')->default(0)->after('avg_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn(['avg_rating', 'reviews_count']);
        });
    }
};