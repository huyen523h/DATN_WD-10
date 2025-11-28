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
        if (Schema::hasTable('invoices')) {
            return;
        }

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('tour_id')->constrained()->onDelete('cascade');
            
            // Company Information
            $table->string('company_name')->default('Tour365');
            $table->text('company_address')->nullable();
            $table->string('company_phone')->nullable();
            $table->string('company_email')->nullable();
            $table->string('company_tax_code')->nullable();
            
            // Customer Information
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->text('customer_address')->nullable();
            
            // Tour Information
            $table->string('tour_title');
            $table->date('departure_date');
            
            // Guest Information
            $table->integer('adults')->default(0);
            $table->integer('children')->default(0);
            $table->integer('infants')->default(0);
            
            // Pricing
            $table->decimal('adult_price', 10, 2)->default(0);
            $table->decimal('child_price', 10, 2)->default(0);
            $table->decimal('infant_price', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(10); // 10% VAT
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            
            // Payment Information
            $table->string('payment_method')->default('bank_transfer');
            $table->enum('payment_status', ['pending', 'paid', 'cancelled', 'refunded'])->default('pending');
            
            // Invoice Dates
            $table->datetime('invoice_date');
            $table->datetime('due_date');
            
            // Additional Information
            $table->text('notes')->nullable();
            $table->string('pdf_path')->nullable();
            $table->enum('status', ['draft', 'sent', 'paid', 'cancelled'])->default('draft');
            
            $table->timestamps();
            
            // Indexes
            $table->index(['invoice_number']);
            $table->index(['booking_id']);
            $table->index(['user_id']);
            $table->index(['payment_status']);
            $table->index(['invoice_date']);
            $table->index(['due_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
