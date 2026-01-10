<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('check_ins', function (Blueprint $table) {
            $table->timestamp('check_in_time')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('check_ins', function (Blueprint $table) {
            $table->timestamp('check_in_time')->nullable(false)->change();
        });
    }
};
