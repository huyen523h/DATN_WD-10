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
        Schema::create('check_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('departure_id')->constrained('tour_departures')->onDelete('cascade');
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('checked_by')->constrained('users')->onDelete('cascade'); // Guide ID
            $table->datetime('check_in_time');
            $table->string('check_in_location')->nullable();
            $table->enum('status', ['checked_in', 'checked_out', 'absent'])->default('checked_in');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['departure_id', 'booking_id', 'check_in_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_ins');
    }
};
