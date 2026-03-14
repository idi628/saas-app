<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        // Gather key business metrics
        $totalCustomers = Customer::count();
        
        $totalRevenue = Invoice::sum('total_amount');
        
        // Grab products with 5 or fewer items left in stock
        $lowStockProducts = Product::where('stock_quantity', '<=', 5)->get();
        
        // Grab the 5 most recent invoices
        $recentInvoices = Invoice::with('customer')->latest()->take(5)->get();

        return view('dashboard', compact('totalCustomers', 'totalRevenue', 'lowStockProducts', 'recentInvoices'));
    }
}