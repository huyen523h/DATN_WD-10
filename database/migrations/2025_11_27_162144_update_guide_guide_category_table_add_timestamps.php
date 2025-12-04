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
        Schema::table('guide_guide_category', function (Blueprint $table) {
            if (!Schema::hasColumn('guide_guide_category', 'created_at')) {
                $table->timestamp('created_at')->nullable()->after('guide_category_id');
            }
            if (!Schema::hasColumn('guide_guide_category', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guide_guide_category', function (Blueprint $table) {
            if (Schema::hasColumn('guide_guide_category', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
            if (Schema::hasColumn('guide_guide_category', 'created_at')) {
                $table->dropColumn('created_at');
            }
        });
    }
};
