<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->hasPermission('pos', 'read'), 403, 'You do not have permission to view products.');

        $products = Product::orderBy('name')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        abort_if(!auth()->user()->hasPermission('pos', 'create'), 403, 'You do not have permission to create products.');
        return view('products.create');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasPermission('pos', 'create'), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        Product::create($validated);

        return redirect()->route('tenant.pos.index', [
            'tenant_slug' => auth()->user()->tenant->slug
        ])->with('success', 'Product added successfully!');
    }

    public function edit(Product $product)
    {
        abort_if(!auth()->user()->hasPermission('pos', 'update'), 403, 'You do not have permission to edit products.');
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        abort_if(!auth()->user()->hasPermission('pos', 'update'), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $product->update($validated);

        return redirect()->route('tenant.pos.index', [
            'tenant_slug' => auth()->user()->tenant->slug
        ])->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        abort_if(!auth()->user()->hasPermission('pos', 'delete'), 403, 'You do not have permission to delete products.');

        $product->delete();

        return redirect()->route('tenant.pos.index', [
            'tenant_slug' => auth()->user()->tenant->slug
        ])->with('success', 'Product deleted successfully!');
    }
}