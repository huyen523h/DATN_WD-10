<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tour_departures', function (Blueprint $table) {
            if (!Schema::hasColumn('tour_departures', 'vehicle_id')) {
                $table->foreignId('vehicle_id')
                    ->nullable()
                    ->after('guide_id')
                    ->constrained('vehicles')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('tour_departures', function (Blueprint $table) {
            if (Schema::hasColumn('tour_departures', 'vehicle_id')) {
                $table->dropForeign(['vehicle_id']);
                $table->dropColumn('vehicle_id');
            }
        });
    }
};


