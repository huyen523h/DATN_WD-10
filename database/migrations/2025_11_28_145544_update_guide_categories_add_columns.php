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
        Schema::table('guide_categories', function (Blueprint $table) {
            if (!Schema::hasColumn('guide_categories', 'name')) {
                $table->string('name')->after('id');
            }
            if (!Schema::hasColumn('guide_categories', 'slug')) {
                $table->string('slug')->nullable()->after('name');
            }
            if (!Schema::hasColumn('guide_categories', 'type')) {
                $table->string('type')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('guide_categories', 'description')) {
                $table->text('description')->nullable()->after('type');
            }
            if (!Schema::hasColumn('guide_categories', 'is_default')) {
                $table->boolean('is_default')->default(false)->after('description');
            }
            if (!Schema::hasColumn('guide_categories', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('is_default');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guide_categories', function (Blueprint $table) {
            $columns = ['is_active', 'is_default', 'description', 'type', 'slug', 'name'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('guide_categories', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
