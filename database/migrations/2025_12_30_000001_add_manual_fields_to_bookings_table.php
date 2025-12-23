<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('source', 50)->default('website')->after('status');
            $table->decimal('paid_amount', 12, 2)->default(0)->after('total_amount');
            $table->string('payment_method', 50)->nullable()->after('paid_amount');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['source', 'paid_amount', 'payment_method']);
        });
    }
};

