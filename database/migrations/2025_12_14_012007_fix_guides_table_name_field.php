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
        Schema::table('guides', function (Blueprint $table) {
            // Kiểm tra xem có trường 'name' không
            if (Schema::hasColumn('guides', 'name')) {
                // Nếu có, thêm default value hoặc làm nullable
                $table->string('name')->nullable()->default('')->change();
            } else {
                // Nếu không có, thêm trường name với default value
                $table->string('name')->nullable()->default('')->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guides', function (Blueprint $table) {
            // Không làm gì trong down vì chúng ta không muốn xóa dữ liệu
        });
    }
};