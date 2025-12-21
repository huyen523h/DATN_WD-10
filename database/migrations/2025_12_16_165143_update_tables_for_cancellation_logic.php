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
        // 1. Cập nhật bảng Bookings: Thêm cột Lý do hủy
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'cancel_reason')) {
                // Lưu lý do hủy tour (VD: Khách bận, Hủy do bão...)
                $table->text('cancel_reason')->nullable()->after('status');
            }
        });

        // 2. Cập nhật bảng Payments: Thêm cột Ảnh bằng chứng hoàn tiền
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'refund_proof')) {
                // Lưu đường dẫn ảnh bill chuyển khoản hoàn tiền
                $table->string('refund_proof')->nullable()->after('note');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'cancel_reason')) {
                $table->dropColumn('cancel_reason');
            }
        });

        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'refund_proof')) {
                $table->dropColumn('refund_proof');
            }
        });
    }
};