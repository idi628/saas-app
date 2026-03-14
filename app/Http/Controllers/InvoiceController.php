<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;
use App\Models\Product;

class InvoiceController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->hasPermission('invoicing', 'read'), 403, 'You do not have permission to view invoices.');

        $invoices = Invoice::with('customer')->latest()->get();
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        abort_if(!auth()->user()->hasPermission('invoicing', 'create'), 403, 'You do not have permission to generate invoices.');

        $customers = Customer::orderBy('name')->get();
        $products = Product::where('stock_quantity', '>', 0)->orderBy('name')->get();
        
        $nextId = Invoice::max('id') + 1;
        $invoiceNumber = 'INV-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('invoices.create', compact('customers', 'products', 'invoiceNumber'));
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasPermission('invoicing', 'create'), 403);

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_number' => 'required|string|max:255',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $invoice = Invoice::create([
            'customer_id' => $validated['customer_id'],
            'invoice_number' => $validated['invoice_number'],
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'] ?? null,
            'total_amount' => 0,
            'status' => 'pending',
        ]);

        $grandTotal = 0;

        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            $lineTotal = $product->price * $item['quantity'];
            $grandTotal += $lineTotal;

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
            ]);

            $product->decrement('stock_quantity', $item['quantity']);
        }

        $invoice->update(['total_amount' => $grandTotal]);

        return redirect()->route('tenant.invoices.index', [
            'tenant_slug' => auth()->user()->tenant->slug
        ])->with('success', 'Invoice created successfully!');
    }

    public function show(Invoice $invoice)
    {
        abort_if(!auth()->user()->hasPermission('invoicing', 'read'), 403, 'You do not have permission to view invoices.');

        $invoice->load('customer', 'items.product');
        return view('invoices.show', compact('invoice'));
    }
}