<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('group_tour_requests', function (Blueprint $table) {
            $table->id();
            
            // A. Thông tin liên hệ
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('organization')->nullable(); // Tên công ty/tổ chức

            // B. Thông tin chuyến đi
            $table->string('destination'); // Điểm đến
            $table->date('departure_date'); // Ngày đi dự kiến
            $table->string('duration')->nullable(); // Thời gian (3N2Đ...)
            
            // Số lượng khách
            $table->integer('adults')->default(1);
            $table->integer('children')->default(0);
            $table->integer('infants')->default(0);
            
            $table->string('budget')->nullable(); // Ngân sách dự kiến

            // C. Dịch vụ & Ghi chú
            // Lưu mảng các dịch vụ (Teambuilding, Gala...) dưới dạng JSON
            $table->json('services')->nullable(); 
            $table->text('note')->nullable(); // Ghi chú của khách

            // Quản lý của Admin/Staff
            // status: pending (Mới), contacted (Đang tư vấn), contracted (Đã chốt), cancelled (Hủy)
            $table->string('status')->default('pending'); 
            $table->text('admin_notes')->nullable(); // Ghi chú của Sale

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_tour_requests');
    }
};