<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            // Thời lượng (phần này an toàn vì 'duration' đã có từ trước)
            if (!Schema::hasColumn('tours', 'duration_days')) {
                $table->integer('duration_days')->nullable()->after('duration');
            }
            if (!Schema::hasColumn('tours', 'nights')) {
                $table->integer('nights')->nullable()->after('duration_days');
            }

            // Tabs nội dung (SỬA LỖI TẠI ĐÂY)
            // Chú thích: Xóa ->after('excludes') VÌ CÓ THỂ CỘT 'excludes' CHƯA TỒN TẠI.
            // Laravel sẽ tự động thêm cột này vào cuối bảng, điều này an toàn 100%.
            if (!Schema::hasColumn('tours', 'surcharges')) {
                $table->text('surcharges')->nullable(); // Phụ thu
            }
            
            // Chú thích: Xóa ->after('cancellation_policy') VÌ LÝ DO TƯƠNG TỰ.
            if (!Schema::hasColumn('tours', 'visa_requirements')) {
                $table->text('visa_requirements')->nullable(); // Visa
            }

            // Giá khuyến mãi (SỬA LỖI TẠI ĐÂY)
            // Chú thích: Xóa ->after('original_price') ĐỂ ĐẢM BẢO AN TOÀN TUYỆT ĐỐI.
            if (!Schema::hasColumn('tours', 'discount_price')) {
                $table->decimal('discount_price', 12, 2)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $cols = ['duration_days', 'nights', 'surcharges', 'visa_requirements', 'discount_price'];
            $drop = [];
            foreach ($cols as $c) {
                if (Schema::hasColumn('tours', $c)) {
                    $drop[] = $c;
                }
            }
            if (!empty($drop)) {
                $table->dropColumn($drop);
            }
        });
    }
};