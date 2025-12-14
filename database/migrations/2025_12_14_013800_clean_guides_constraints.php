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
        // Xóa tất cả unique constraints không cần thiết từ bảng guides
        try {
            $constraints = [
                'guides_phone_unique',
                'guides_email_unique', 
                'phone_unique',
                'email_unique'
            ];
            
            foreach ($constraints as $constraint) {
                try {
                    DB::statement("ALTER TABLE guides DROP INDEX {$constraint}");
                    echo "Dropped constraint: {$constraint}\n";
                } catch (\Exception $e) {
                    // Ignore if constraint doesn't exist
                }
            }
            
        } catch (\Exception $e) {
            echo "Error cleaning constraints: " . $e->getMessage() . "\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Không làm gì trong rollback để tránh tạo lại constraints có vấn đề
    }
};