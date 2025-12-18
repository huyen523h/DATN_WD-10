<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicles', 'brand')) {
                $table->string('brand')->nullable()->after('vehicle_type');
            }
            if (!Schema::hasColumn('vehicles', 'year')) {
                $table->unsignedSmallInteger('year')->nullable()->after('brand');
            }
            if (!Schema::hasColumn('vehicles', 'color')) {
                $table->string('color')->nullable()->after('year');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            if (Schema::hasColumn('vehicles', 'brand')) {
                $table->dropColumn('brand');
            }
            if (Schema::hasColumn('vehicles', 'year')) {
                $table->dropColumn('year');
            }
            if (Schema::hasColumn('vehicles', 'color')) {
                $table->dropColumn('color');
            }
        });
    }
};


