<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TenantSettingsController extends Controller
{
    // Show the settings form
    public function edit()
    {
        // Grab the active company from the logged-in user
        $tenant = auth()->user()->tenant;
        
        return view('settings.edit', compact('tenant'));
    }

    // Save the changes
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $tenant = auth()->user()->tenant;
        
        // Update the company name
        $tenant->update([
            'name' => $validated['name'],
        ]);

        return redirect()->route('tenant.settings.edit', [
            'tenant_slug' => $tenant->slug
        ])->with('success', 'Company settings updated successfully!');
    }
}