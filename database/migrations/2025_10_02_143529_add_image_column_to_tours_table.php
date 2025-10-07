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
        if (!Schema::hasColumn('tours', 'image')) {
            Schema::table('tours', function (Blueprint $table) {
                $table->string('image')->nullable()->after('departure_date');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('tours', 'image')) {
            Schema::table('tours', function (Blueprint $table) {
                $table->dropColumn('image');
            });
        }
    }
};
