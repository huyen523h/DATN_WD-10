<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thêm cột lưu dịch vụ thêm & tổng tiền dịch vụ vào bảng bookings.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'additional_services')) {
                $table->json('additional_services')->nullable()->after('infants');
            }
            if (!Schema::hasColumn('bookings', 'additional_services_total')) {
                $table->decimal('additional_services_total', 12, 2)->default(0)->after('additional_services');
            }
        });
    }

    /**
     * Rollback.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'additional_services_total')) {
                $table->dropColumn('additional_services_total');
            }
            if (Schema::hasColumn('bookings', 'additional_services')) {
                $table->dropColumn('additional_services');
            }
        });
    }
};


