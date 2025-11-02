<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tour_departures', function (Blueprint $table) {
            if (!Schema::hasColumn('tour_departures', 'price')) {
                $table->decimal('price', 12, 2)->nullable()->after('seats_available');
            }
            if (!Schema::hasColumn('tour_departures', 'child_price')) {
                $table->decimal('child_price', 12, 2)->nullable()->after('price');
            }
            if (!Schema::hasColumn('tour_departures', 'infant_price')) {
                $table->decimal('infant_price', 12, 2)->nullable()->after('child_price');
            }
            if (!Schema::hasColumn('tour_departures', 'status')) {
                $table->string('status', 20)->default('available')->after('infant_price'); // available|contact|sold_out
            }
        });
    }

    public function down(): void
    {
        Schema::table('tour_departures', function (Blueprint $table) {
            $cols = ['price','child_price','infant_price','status'];
            $drop = [];
            foreach ($cols as $c) {
                if (Schema::hasColumn('tour_departures', $c)) $drop[] = $c;
            }
            if ($drop) $table->dropColumn($drop);
        });
    }
};
