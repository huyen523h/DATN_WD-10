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
        if (Schema::hasTable('guide_documents')) {
            return;
        }

        Schema::create('guide_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guide_id')->constrained('guides')->onDelete('cascade');
            $table->string('type'); // license, certificate, contract, etc.
            $table->string('name');
            $table->string('file_path')->nullable();
            $table->string('issued_by')->nullable();
            $table->date('issued_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->enum('status', ['valid', 'expired', 'revoked', 'pending'])->default('valid');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['guide_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guide_documents');
    }
};
