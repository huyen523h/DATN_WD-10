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
        // 1. Cập nhật bảng promotions
        Schema::table('promotions', function (Blueprint $table) {
            // Thêm cột tổng số lượng phát hành
            if (!Schema::hasColumn('promotions', 'quantity')) {
                $table->integer('quantity')->default(100)->after('discount_amount')->comment('Tổng số lượng mã phát hành');
            }
            
            // Thêm cột đếm số lần đã dùng
            if (!Schema::hasColumn('promotions', 'used_count')) {
                $table->integer('used_count')->default(0)->after('quantity')->comment('Số lần đã được sử dụng');
            }

            // Đảm bảo có cột min_order_value (Nếu chưa có thì thêm, nếu có rồi thì thôi)
            if (!Schema::hasColumn('promotions', 'min_order_value')) {
                $table->decimal('min_order_value', 12, 2)->nullable()->default(0)->after('discount_amount')->comment('Giá trị đơn tối thiểu');
            }
        });

        // 2. Tạo bảng lịch sử sử dụng (promotion_usages)
        if (!Schema::hasTable('promotion_usages')) {
            Schema::create('promotion_usages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('promotion_id');
                $table->unsignedBigInteger('booking_id')->nullable(); // Để biết dùng cho đơn nào (để hoàn mã khi hủy)
                $table->timestamp('used_at')->useCurrent();
                
                // Khóa ngoại
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('promotion_id')->references('id')->on('promotions')->onDelete('cascade');
                $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_usages');

        Schema::table('promotions', function (Blueprint $table) {
            $table->dropColumn(['quantity', 'used_count']);
            // Không drop min_order_value vì có thể bảng cũ đã có
        });
    }
};