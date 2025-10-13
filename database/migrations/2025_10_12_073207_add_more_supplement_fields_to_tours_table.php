<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            // Thời lượng (không đụng cột 'duration' đang có dạng text "3 ngày 2 đêm")
            if (!Schema::hasColumn('tours', 'duration_days')) {
                $table->integer('duration_days')->nullable()->after('duration');
            }
            if (!Schema::hasColumn('tours', 'nights')) {
                $table->integer('nights')->nullable()->after('duration_days');
            }

            // Tabs nội dung
            if (!Schema::hasColumn('tours', 'surcharges')) {
                $table->text('surcharges')->nullable()->after('excludes'); // Phụ thu
            }
            if (!Schema::hasColumn('tours', 'visa_requirements')) {
                $table->text('visa_requirements')->nullable()->after('cancellation_policy'); // Visa
            }

            // Giá khuyến mãi
            if (!Schema::hasColumn('tours', 'discount_price')) {
                $table->decimal('discount_price', 12, 2)->nullable()->after('original_price');
            }

            // Nếu thiếu 3 mức giá tổng quát của tour (ảnh của bạn đang có rồi) thì mở comment:
            // if (!Schema::hasColumn('tours', 'price_adult'))  $table->decimal('price_adult',12,2)->nullable()->after('discount_price');
            // if (!Schema::hasColumn('tours', 'price_child'))  $table->decimal('price_child',12,2)->nullable()->after('price_adult');
            // if (!Schema::hasColumn('tours', 'price_infant')) $table->decimal('price_infant',12,2)->nullable()->after('price_child');
        });
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $cols = ['duration_days','nights','surcharges','visa_requirements','discount_price'];
            $drop = [];
            foreach ($cols as $c) {
                if (Schema::hasColumn('tours', $c)) $drop[] = $c;
            }
            if ($drop) $table->dropColumn($drop);
        });
    }
};
