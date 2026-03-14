<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $tenants = Tenant::orderBy('name')->get();
        return view('admin.index', compact('tenants'));
    }

    public function updateModules(Request $request, Tenant $tenant)
    {
        // 1. Validate the incoming data
        $request->validate([
            'max_users' => 'required|integer|min:1',
            'modules' => 'nullable|array',
        ]);

        // 2. Update the company's user limit!
        $tenant->update([
            'max_users' => $request->max_users,
        ]);

        // 3. Process the Module Checkboxes
        $selectedModules = $request->input('modules', []); 
        $availableModules = ['crm', 'pos', 'invoicing'];

        foreach ($availableModules as $module) {
            $enabled = in_array($module, $selectedModules);
            
            $exists = DB::table('tenant_modules')
                ->where('tenant_id', $tenant->id)
                ->where('module_key', $module)
                ->exists();

            if ($exists) {
                DB::table('tenant_modules')
                    ->where('tenant_id', $tenant->id)
                    ->where('module_key', $module)
                    ->update(['enabled' => $enabled, 'updated_at' => now()]);
            } else {
                DB::table('tenant_modules')->insert([
                    'tenant_id' => $tenant->id, 
                    'module_key' => $module, 
                    'enabled' => $enabled, 
                    'created_at' => now(), 
                    'updated_at' => now()
                ]);
            }
        }

        return back()->with('success', 'Account settings updated successfully for ' . $tenant->name);
    }
}