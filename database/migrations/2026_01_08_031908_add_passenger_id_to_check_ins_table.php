<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('check_ins', function (Blueprint $table) {
            $table->unsignedBigInteger('passenger_id')
                ->nullable()
                ->after('departure_id');

            $table->index('passenger_id');
        });
    }

    public function down()
    {
        Schema::table('check_ins', function (Blueprint $table) {
            $table->dropColumn('passenger_id');
        });
    }
};
