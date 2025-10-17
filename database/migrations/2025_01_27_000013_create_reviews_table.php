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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('rating'); // Chú thích: Đổi thành unsignedTinyInteger cho hiệu năng tốt hơn (chỉ lưu số 1-5)
            $table->text('comment')->nullable();
            $table->text('images')->nullable();
            // Đổi default từ 'visible' thành 'pending'
            // Thêm các trạng thái 'approved' và 'rejected' để quản lý rõ ràng hơn
            $table->string('status', 20)->default('pending'); 

            $table->timestamps();

            // Chú thích: Thêm ràng buộc để ngăn 1 user review 1 tour nhiều lần
            $table->unique(['tour_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};