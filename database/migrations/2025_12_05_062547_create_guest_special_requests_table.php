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
        Schema::create('guest_special_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('departure_id')->constrained('tour_departures')->onDelete('cascade');
            $table->enum('request_type', ['dietary', 'medical', 'accessibility', 'other'])->default('other');
            $table->string('title');
            $table->text('description');
            $table->enum('status', ['pending', 'acknowledged', 'fulfilled', 'cancelled'])->default('pending');
            $table->text('notes')->nullable(); // Notes from guide
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null'); // Guide ID
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_special_requests');
    }
};
