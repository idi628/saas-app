<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            
            // The Security Lock
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            
            // Link to the Customer
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            
            // Invoice Details
            $table->string('invoice_number');
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('status')->default('pending'); // pending, paid, cancelled
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};