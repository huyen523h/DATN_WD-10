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
        Schema::table('tours', function (Blueprint $table) {
            $table->date('departure_date')->nullable()->after('available_seats'); // Thêm ngày khởi hành
            $table->string('image', 255)->nullable()->after('departure_date'); // Thêm path hình ảnh
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn(['departure_date', 'image']);
        });
    }
};