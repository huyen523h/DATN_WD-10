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
        Schema::create('guide_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('departure_id')->constrained('tour_departures')->onDelete('cascade');
            $table->foreignId('guide_id')->constrained('users')->onDelete('cascade');
            $table->enum('feedback_type', ['tour', 'service', 'supplier', 'other'])->default('other');
            $table->string('subject');
            $table->text('content');
            $table->integer('rating')->nullable(); // 1-5 stars
            $table->json('images')->nullable();
            $table->string('supplier_name')->nullable(); // Tên nhà cung cấp nếu feedback_type = supplier
            $table->text('suggestions')->nullable(); // Đề xuất cải thiện
            $table->enum('status', ['pending', 'reviewed', 'resolved', 'dismissed'])->default('pending');
            $table->text('admin_response')->nullable(); // Phản hồi từ admin
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guide_feedback');
    }
};
