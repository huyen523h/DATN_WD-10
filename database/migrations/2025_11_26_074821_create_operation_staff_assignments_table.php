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
        if (Schema::hasTable('operation_staff_assignments')) {
            return;
        }

        Schema::create('operation_staff_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_operation_id')->constrained('tour_operations')->onDelete('cascade');
            $table->foreignId('guide_id')->nullable()->constrained('guides')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('external_name')->nullable();
            $table->string('role'); // guide, driver, logistics, support
            $table->enum('assignment_type', ['internal', 'external'])->default('internal');
            $table->enum('status', ['pending', 'notified', 'confirmed', 'declined'])->default('pending');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('declined_at')->nullable();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['tour_operation_id', 'role', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operation_staff_assignments');
    }
};
