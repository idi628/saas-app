<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        @isset($totalCustomers)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex items-center border border-gray-100">
                    <div class="p-4 rounded-full bg-indigo-50 text-indigo-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <p class="mb-1 text-sm font-medium text-gray-500 uppercase tracking-wide">Total Customers</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalCustomers }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex items-center border border-gray-100">
                    <div class="p-4 rounded-full bg-green-50 text-green-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="mb-1 text-sm font-medium text-gray-500 uppercase tracking-wide">Total Invoiced</p>
                        <p class="text-3xl font-bold text-gray-900">${{ number_format($totalRevenue, 2) }}</p>
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                    <div class="p-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-800">Inventory Alerts</h3>
                    </div>
                    <div class="p-4">
                        @if($lowStockProducts->count() > 0)
                            <ul class="divide-y divide-gray-100">
                                @foreach($lowStockProducts as $product)
                                    <li class="py-3 flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-900">{{ $product->name }}</span>
                                        <span class="px-3 py-1 bg-red-50 text-red-700 rounded-full text-xs font-bold border border-red-100">
                                            Only {{ $product->stock_quantity }} left
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-center py-6">
                                <p class="text-gray-500 text-sm">All inventory is well-stocked!</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                    <div class="p-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-800">Recent Activity</h3>
                    </div>
                    <div class="p-4">
                        @if($recentInvoices->count() > 0)
                            <ul class="divide-y divide-gray-100">
                                @foreach($recentInvoices as $invoice)
                                    <li class="py-3 flex justify-between items-center">
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">{{ $invoice->customer->name }}</p>
                                            <p class="text-xs text-gray-500">Invoice #{{ $invoice->invoice_number }} &bull; {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}</p>
                                        </div>
                                        <span class="text-sm font-bold text-indigo-600">${{ number_format($invoice->total_amount, 2) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-center py-6">
                                <p class="text-gray-500 text-sm">No recent invoices.</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        @endisset

    </div>
</x-app-layout>