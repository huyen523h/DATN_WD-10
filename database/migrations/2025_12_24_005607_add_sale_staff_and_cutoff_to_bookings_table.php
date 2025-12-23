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
            // Sale phụ trách booking
            $table->unsignedBigInteger('sale_staff_id')->nullable()->after('booking_source');
            $table->foreign('sale_staff_id')->references('id')->on('users')->onDelete('set null');
        });
        
        // Thêm cutoff_days vào tour_departures nếu chưa có
        if (!Schema::hasColumn('tour_departures', 'cutoff_days')) {
            Schema::table('tour_departures', function (Blueprint $table) {
                $table->integer('cutoff_days')->default(3)->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['sale_staff_id']);
            $table->dropColumn('sale_staff_id');
        });
        
        if (Schema::hasColumn('tour_departures', 'cutoff_days')) {
            Schema::table('tour_departures', function (Blueprint $table) {
                $table->dropColumn('cutoff_days');
            });
        }
    }
};
