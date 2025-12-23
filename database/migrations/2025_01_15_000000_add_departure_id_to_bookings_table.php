<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'departure_id')) {
                $table->unsignedBigInteger('departure_id')->nullable()->after('tour_id');
                $table->foreign('departure_id')->references('id')->on('tour_departures')->nullOnDelete();
            }
        });

        // Backfill departure_id cho dữ liệu cũ nếu có cột departure_date
        if (
            Schema::hasColumn('bookings', 'departure_id') &&
            Schema::hasColumn('bookings', 'departure_date')
        ) {
            DB::table('bookings as b')
                ->join('tour_departures as d', function ($join) {
                    $join->on('d.tour_id', '=', 'b.tour_id')
                        ->on('d.departure_date', '=', 'b.departure_date');
                })
                ->whereNull('b.departure_id')
                ->update(['b.departure_id' => DB::raw('d.id')]);
        }
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'departure_id')) {
                $table->dropForeign(['departure_id']);
                $table->dropColumn('departure_id');
            }
        });
    }
};

