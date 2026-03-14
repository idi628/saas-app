<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        // Require READ access
        abort_if(!auth()->user()->hasPermission('crm', 'read'), 403, 'You do not have permission to view customers.');

        $customers = Customer::orderBy('name')->get();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        // Require CREATE access
        abort_if(!auth()->user()->hasPermission('crm', 'create'), 403, 'You do not have permission to create customers.');
        return view('customers.create');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasPermission('crm', 'create'), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        Customer::create($validated);

        return redirect()->route('tenant.customers.index', [
            'tenant_slug' => auth()->user()->tenant->slug
        ])->with('success', 'Customer created successfully!');
    }

    public function edit(Customer $customer)
    {
        // Require UPDATE access
        abort_if(!auth()->user()->hasPermission('crm', 'update'), 403, 'You do not have permission to edit customers.');
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        abort_if(!auth()->user()->hasPermission('crm', 'update'), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $customer->update($validated);

        return redirect()->route('tenant.customers.index', [
            'tenant_slug' => auth()->user()->tenant->slug
        ])->with('success', 'Customer updated successfully!');
    }

    public function destroy(Customer $customer)
    {
        // Require DELETE access
        abort_if(!auth()->user()->hasPermission('crm', 'delete'), 403, 'You do not have permission to delete customers.');

        $customer->delete();

        return redirect()->route('tenant.customers.index', [
            'tenant_slug' => auth()->user()->tenant->slug
        ])->with('success', 'Customer deleted successfully!');
    }
}