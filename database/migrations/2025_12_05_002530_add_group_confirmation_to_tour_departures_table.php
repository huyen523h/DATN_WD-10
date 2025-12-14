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
            // B2: Chốt đoàn - Trạng thái và số lượng khách đã chốt
            $table->boolean('group_confirmed')->default(false)->after('status');
            $table->integer('confirmed_guests_count')->nullable()->after('group_confirmed');
            $table->timestamp('group_confirmed_at')->nullable()->after('confirmed_guests_count');
            $table->foreignId('group_confirmed_by')->nullable()->constrained('users')->nullOnDelete()->after('group_confirmed_at');
            
            // B4: Thông tin xe - Bổ sung thêm loại xe
            $table->enum('vehicle_type', ['16', '29', '45'])->nullable()->after('vehicle_details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tour_departures', function (Blueprint $table) {
            $table->dropForeign(['group_confirmed_by']);
            $table->dropColumn([
                'group_confirmed',
                'confirmed_guests_count',
                'group_confirmed_at',
                'group_confirmed_by',
                'vehicle_type'
            ]);
        });
    }
};
