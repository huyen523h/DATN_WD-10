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
        // Nếu chưa có bảng vehicles thì tạo mới
        if (!Schema::hasTable('vehicles')) {
            Schema::create('vehicles', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('name'); // Tên xe / mô tả ngắn
                $table->string('license_plate')->unique(); // Biển số
                $table->string('vehicle_type')->nullable(); // bus, limousine, car...
                $table->unsignedInteger('capacity')->default(16); // Số chỗ
                $table->string('status')->default('available'); // available, maintenance, inactive
                $table->string('driver_name')->nullable();
                $table->string('driver_phone')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        } else {
            // Nếu bảng đã tồn tại (dự án cũ) thì chỉ thêm các cột còn thiếu
            Schema::table('vehicles', function (Blueprint $table) {
                if (!Schema::hasColumn('vehicles', 'code')) {
                    $table->string('code')->nullable()->after('id');
                }
                if (!Schema::hasColumn('vehicles', 'name')) {
                    $table->string('name')->nullable();
                }
                if (!Schema::hasColumn('vehicles', 'license_plate')) {
                    $table->string('license_plate')->nullable();
                }
                if (!Schema::hasColumn('vehicles', 'vehicle_type')) {
                    $table->string('vehicle_type')->nullable();
                }
                if (!Schema::hasColumn('vehicles', 'capacity')) {
                    $table->unsignedInteger('capacity')->default(16);
                }
                if (!Schema::hasColumn('vehicles', 'status')) {
                    $table->string('status')->default('available');
                }
                if (!Schema::hasColumn('vehicles', 'driver_name')) {
                    $table->string('driver_name')->nullable();
                }
                if (!Schema::hasColumn('vehicles', 'driver_phone')) {
                    $table->string('driver_phone')->nullable();
                }
                if (!Schema::hasColumn('vehicles', 'notes')) {
                    $table->text('notes')->nullable();
                }
                if (!Schema::hasColumn('vehicles', 'created_at')) {
                    $table->timestamps();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};


