<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('image_url', 500);
            $table->string('link_url', 500)->nullable();
            $table->enum('type', ['hero', 'promotion', 'category', 'featured'])->default('hero');
            $table->enum('position', ['top', 'middle', 'bottom', 'sidebar'])->default('top');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->json('target_audience')->nullable(); // ['all', 'new_users', 'returning_users']
            $table->integer('click_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->timestamps();

            // Indexes for better performance
            $table->index(['is_active', 'type']);
            $table->index(['start_date', 'end_date']);
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
