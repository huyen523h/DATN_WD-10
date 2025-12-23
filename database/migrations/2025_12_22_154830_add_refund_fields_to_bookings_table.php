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
            // 1. Thông tin tài khoản khách hàng (Để Admin chuyển tiền)
            $table->string('refund_bank')->nullable()->comment('Tên ngân hàng thụ hưởng');
            $table->string('refund_account')->nullable()->comment('Số tài khoản nhận tiền');
            $table->string('refund_holder')->nullable()->comment('Tên chủ tài khoản');

            // 2. Thông tin xử lý hoàn tiền của Admin
            $table->string('refund_proof_image')->nullable()->comment('Ảnh bill chuyển khoản hoàn tiền của Admin');
            $table->timestamp('cancel_requested_at')->nullable()->comment('Thời gian khách gửi yêu cầu hủy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'refund_bank',
                'refund_account',
                'refund_holder',
                'refund_proof_image',
                'cancel_requested_at'
            ]);
        });
    }
};