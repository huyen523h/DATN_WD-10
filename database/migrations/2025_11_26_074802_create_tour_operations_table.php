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
        if (Schema::hasTable('tour_operations')) {
            return;
        }

        Schema::create('tour_operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained('tours')->onDelete('cascade');
            $table->foreignId('tour_departure_id')->constrained('tour_departures')->onDelete('cascade');
            $table->string('operation_code')->unique();
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime')->nullable();
            $table->string('meeting_point')->nullable();
            $table->enum('status', ['planning', 'confirmed', 'in_progress', 'completed', 'cancelled'])->default('planning');
            $table->text('notes')->nullable();
            $table->json('itinerary_snapshot')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['tour_id', 'tour_departure_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_operations');
    }
};
