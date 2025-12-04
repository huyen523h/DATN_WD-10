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
        if (Schema::hasTable('operation_services')) {
            return;
        }

        Schema::create('operation_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_operation_id')->constrained('tour_operations')->onDelete('cascade');
            $table->string('service_type'); // vehicle, hotel, flight, restaurant, ticket, other
            $table->string('provider_name')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('booking_code')->nullable();
            $table->unsignedInteger('quantity')->default(0);
            $table->decimal('cost', 12, 2)->default(0);
            $table->enum('status', ['draft', 'requested', 'confirmed', 'cancelled'])->default('draft');
            $table->timestamp('confirmation_deadline')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->text('requirements')->nullable();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['tour_operation_id', 'service_type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operation_services');
    }
};
