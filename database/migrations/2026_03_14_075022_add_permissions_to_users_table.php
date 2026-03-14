<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Identifies the true owner of the company workspace
            $table->boolean('is_tenant_owner')->default(false)->after('is_admin');
            // Stores the custom checkbox matrix as a flexible JSON array
            $table->json('permissions')->nullable()->after('is_tenant_owner');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_tenant_owner', 'permissions']);
        });
    }
};