<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * (Sửa cột 'rating' để CHO PHÉP NULL)
     */
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Sửa cột 'rating' thành 'nullable' (cho phép trống)
            // Lệnh này cần 'doctrine/dbal' mà bạn đã cài
            $table->integer('rating')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     * (Khôi phục lại như cũ nếu cần)
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Sửa cột 'rating' trở lại KHÔNG cho phép NULL
             $table->integer('rating')->nullable(false)->change();
        });
    }
};