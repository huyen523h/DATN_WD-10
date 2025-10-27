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
        Schema::create('check_in_outs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['check_in', 'check_out']);
            $table->timestamp('check_time');
            $table->string('location')->nullable(); // Địa điểm check-in/out
            $table->decimal('latitude', 10, 8)->nullable(); // Vĩ độ GPS
            $table->decimal('longitude', 11, 8)->nullable(); // Kinh độ GPS
            $table->text('notes')->nullable(); // Ghi chú
            $table->string('status')->default('pending'); // pending, confirmed, cancelled
            $table->string('verified_by')->nullable(); // Người xác nhận
            $table->timestamp('verified_at')->nullable(); // Thời gian xác nhận
            $table->json('metadata')->nullable(); // Dữ liệu bổ sung (ảnh, file, etc.)
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'type']);
            $table->index(['booking_id', 'type']);
            $table->index('check_time');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_in_outs');
    }
};
