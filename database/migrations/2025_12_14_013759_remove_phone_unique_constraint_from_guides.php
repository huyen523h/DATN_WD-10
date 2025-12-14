<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Kiểm tra và xóa unique constraint cho phone nếu có
        try {
            // Lấy thông tin về indexes
            $indexes = DB::select("SHOW INDEX FROM guides WHERE Column_name = 'phone'");
            
            foreach ($indexes as $index) {
                if ($index->Key_name !== 'PRIMARY' && strpos($index->Key_name, 'unique') !== false) {
                    DB::statement("ALTER TABLE guides DROP INDEX {$index->Key_name}");
                    echo "Dropped unique index: {$index->Key_name}\n";
                }
            }
            
            // Hoặc thử drop constraint trực tiếp
            DB::statement("ALTER TABLE guides DROP INDEX guides_phone_unique");
            echo "Dropped guides_phone_unique constraint\n";
            
        } catch (\Exception $e) {
            // Ignore errors if constraint doesn't exist
            echo "No phone unique constraint found or already removed\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guides', function (Blueprint $table) {
            // Thêm lại unique constraint nếu cần rollback
            $table->unique('phone', 'guides_phone_unique');
        });
    }
};