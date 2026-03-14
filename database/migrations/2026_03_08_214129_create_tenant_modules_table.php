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
        Schema::create('tenant_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('module_key');
            $table->boolean('enabled')->default(false);
            $table->json('limits_json')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->timestamps();

            // A tenant should only have one record per module
            $table->unique(['tenant_id', 'module_key']); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_modules');
    }
};
