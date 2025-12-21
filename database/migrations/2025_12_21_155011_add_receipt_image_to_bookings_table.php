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
        Schema::table('bookings', function (Blueprint $table) {
            // Thêm cột receipt_image vào sau cột status
            // Cho phép null (để các đơn thanh toán online không bị lỗi)
            $table->string('receipt_image', 255)
                  ->nullable()
                  ->after('status')
                  ->comment('Đường dẫn ảnh phiếu thu/hóa đơn tiền mặt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Xóa cột khi rollback
            $table->dropColumn('receipt_image');
        });
    }
};