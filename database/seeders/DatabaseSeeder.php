<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Core Modules
        DB::table('modules')->insert([
            ['key' => 'crm', 'name' => 'CRM & Customers', 'description' => 'Manage your customers.', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'pos', 'name' => 'Point of Sale', 'description' => 'Process sales.', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 2. Create a Test Tenant (Company)
        $tenantId = DB::table('tenants')->insertGetId([
            'slug' => 'acme-corp',
            'name' => 'Acme Corporation',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Create an Admin User for this Company
        DB::table('users')->insert([
            'name' => 'Admin User',
            'email' => 'admin@acmecorp.com',
            'password' => Hash::make('password'), // The password is just 'password'
            'tenant_id' => $tenantId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Give Acme Corp access to the CRM module (but NOT the POS module)
        DB::table('tenant_modules')->insert([
            'tenant_id' => $tenantId,
            'module_key' => 'crm',
            'enabled' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}