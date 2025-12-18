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
        Schema::create('tour_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('departure_id')->constrained('tour_departures')->onDelete('cascade');
            $table->foreignId('guide_id')->constrained('users')->onDelete('cascade');
            $table->date('log_date');
            $table->enum('type', ['note', 'incident', 'feedback', 'other'])->default('note');
            $table->text('content');
            $table->json('images')->nullable(); // Array of image paths
            $table->string('status')->default('active'); // active, archived
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_logs');
    }
};
