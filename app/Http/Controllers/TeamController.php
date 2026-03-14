<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TeamController extends Controller
{
    public function index()
    {
        $tenant = auth()->user()->tenant;
        $teamMembers = User::where('tenant_id', $tenant->id)->get();
        
        $currentCount = $teamMembers->count();
        $maxUsers = $tenant->max_users;
        
        return view('team.index', compact('teamMembers', 'currentCount', 'maxUsers'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->is_tenant_owner) abort(403, 'Only the owner can add team members.');

        $tenant = auth()->user()->tenant;

        if (User::where('tenant_id', $tenant->id)->count() >= $tenant->max_users) {
            return back()->withErrors(['email' => 'Team limit reached. Please upgrade your account.']);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tenant_id' => $tenant->id,
            'is_tenant_owner' => false, // Employees are NOT owners
            // Default: Give them zero permissions until the owner sets them up!
            'permissions' => [], 
        ]);

        return back()->with('success', 'Team member added successfully! Please configure their permissions.');
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->is_tenant_owner) abort(403, 'Only the owner can remove members.');
        if ($user->id === auth()->id()) return back(); 

        if ($user->tenant_id === auth()->user()->tenant_id) {
            $user->delete();
            return back()->with('success', 'Team member removed.');
        }

        abort(403);
    }

    // NEW: Show the Checkbox Matrix
    public function editPermissions(User $user)
    {
        if (!auth()->user()->is_tenant_owner) abort(403, 'Only the owner can manage permissions.');
        if ($user->tenant_id !== auth()->user()->tenant_id) abort(404);
        if ($user->is_tenant_owner) abort(403, 'Cannot edit the owner account.');

        return view('team.permissions', compact('user'));
    }

    // NEW: Save the Checkbox Matrix
    public function updatePermissions(Request $request, User $user)
    {
        if (!auth()->user()->is_tenant_owner) abort(403);
        if ($user->tenant_id !== auth()->user()->tenant_id) abort(404);

        // Save the array of checked boxes directly into the JSON column
        $user->update([
            'permissions' => $request->input('permissions', [])
        ]);

        return redirect()->route('tenant.team.index', ['tenant_slug' => auth()->user()->tenant->slug])
                         ->with('success', "Permissions updated for {$user->name}");
    }
}