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
        Schema::table('tour_departures', function (Blueprint $table) {
            // Trạng thái tour điều hành
            if (!Schema::hasColumn('tour_departures', 'tour_status')) {
                $table->enum('tour_status', ['preparing', 'running', 'completed', 'has_issue'])
                    ->default('preparing')
                    ->after('preparation_status')
                    ->comment('Trạng thái tour: Chuẩn bị, Đang chạy, Hoàn thành, Có sự cố');
            }
            
            // Ghi chú điều hành
            if (!Schema::hasColumn('tour_departures', 'management_notes')) {
                $table->text('management_notes')->nullable()->after('tour_status');
            }
            
            // File danh sách khách (PDF)
            if (!Schema::hasColumn('tour_departures', 'guest_list_file')) {
                $table->string('guest_list_file')->nullable()->after('management_notes');
            }
            
            // Giờ tập trung (có thể khác với giờ khởi hành)
            if (!Schema::hasColumn('tour_departures', 'assembly_time')) {
                $table->time('assembly_time')->nullable()->after('departure_time');
            }
            
            // Điểm đón (có thể có nhiều điểm)
            if (!Schema::hasColumn('tour_departures', 'pickup_point')) {
                $table->text('pickup_point')->nullable()->after('departure_location');
            }
            
            // Nhà xe (bus company)
            if (!Schema::hasColumn('tour_departures', 'bus_company')) {
                $table->string('bus_company')->nullable()->after('vehicle_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tour_departures', function (Blueprint $table) {
            $table->dropColumn([
                'tour_status',
                'management_notes',
                'guest_list_file',
                'assembly_time',
                'pickup_point',
                'bus_company'
            ]);
        });
    }
};
