<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validate the form data (including our new company name!)
        $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2. Automatically generate a safe URL slug (e.g., "Bob's Burgers" -> "bobs-burgers-8472")
        // We add a random number to guarantee it's unique!
        $slug = Str::slug($request->company_name) . '-' . rand(1000, 9999);

        // 3. Create the Tenant (Company Workspace)
        $tenant = Tenant::create([
            'name' => $request->company_name,
            'slug' => $slug,
        ]);

        // 4. Give this new company the basic "CRM" module for free so their dashboard isn't empty
        DB::table('tenant_modules')->insert([
            'tenant_id' => $tenant->id,
            'module_key' => 'crm',
            'enabled' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 5. Create the User and link them to their new company!
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tenant_id' => $tenant->id, // The magic connection!
        ]);

        event(new Registered($user));

        // 6. Log them in
        Auth::login($user);

        // 7. Redirect them to their brand-new, completely private dashboard
        return redirect()->route('tenant.dashboard', ['tenant_slug' => $tenant->slug]);
    }
}