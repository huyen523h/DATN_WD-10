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
        if (Schema::hasTable('guide_languages')) {
            return;
        }

        Schema::create('guide_languages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guide_id')->constrained('guides')->onDelete('cascade');
            $table->string('language');
            $table->enum('proficiency', ['basic', 'intermediate', 'advanced', 'native'])->default('basic');
            $table->string('certification_code')->nullable();
            $table->date('certified_at')->nullable();
            $table->timestamps();
            $table->unique(['guide_id', 'language']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guide_languages');
    }
};
