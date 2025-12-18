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
            if (!Schema::hasColumn('tour_departures', 'start_time')) {
                $table->time('start_time')->nullable()->after('departure_date');
            }
            if (!Schema::hasColumn('tour_departures', 'end_time')) {
                $table->time('end_time')->nullable()->after('start_time');
            }
            if (!Schema::hasColumn('tour_departures', 'meeting_point')) {
                $table->string('meeting_point')->nullable()->after('seats_available');
            }
            if (!Schema::hasColumn('tour_departures', 'status')) {
                $table->string('status')->default('scheduled')->after('meeting_point');
            }
            if (!Schema::hasColumn('tour_departures', 'status_notes')) {
                $table->text('status_notes')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tour_departures', function (Blueprint $table) {
            $columns = [
                'start_time',
                'end_time',
                'meeting_point',
                'status',
                'status_notes',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('tour_departures', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
