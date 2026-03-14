<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Invoice ') }} #{{ $invoice->invoice_number }}
            </h2>
            <div class="space-x-3">
                <a href="{{ route('tenant.invoices.index', ['tenant_slug' => auth()->user()->tenant->slug]) }}" class="text-gray-600 hover:text-gray-900">
                    &larr; Back to Invoices
                </a>
                <button onclick="window.print()" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-50 text-sm font-medium transition-colors shadow-sm">
                    🖨️ Print / Save PDF
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8 print:py-0 print:max-w-none">
        <div class="bg-white shadow-lg sm:rounded-lg p-10 border border-gray-200 print:shadow-none print:border-none print:p-0">
            
            <div class="flex justify-between items-start border-b border-gray-200 pb-8 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-indigo-600 mb-2">INVOICE</h1>
                    <p class="text-gray-500 text-sm">Invoice Number: <span class="font-medium text-gray-900">{{ $invoice->invoice_number }}</span></p>
                    <p class="text-gray-500 text-sm">Date: <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('F d, Y') }}</span></p>
                    @if($invoice->due_date)
                        <p class="text-gray-500 text-sm">Due Date: <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($invoice->due_date)->format('F d, Y') }}</span></p>
                    @endif
                </div>
                
                <div class="text-right">
                    <h2 class="text-xl font-bold text-gray-900">{{ auth()->user()->tenant->name }}</h2>
                    <p class="text-gray-500 text-sm mt-1">SaaS Platform Generated</p>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Bill To</h3>
                <p class="text-lg font-bold text-gray-900">{{ $invoice->customer->name }}</p>
                @if($invoice->customer->company_name)
                    <p class="text-gray-700">{{ $invoice->customer->company_name }}</p>
                @endif
                @if($invoice->customer->email)
                    <p class="text-gray-500">{{ $invoice->customer->email }}</p>
                @endif
                @if($invoice->customer->phone)
                    <p class="text-gray-500">{{ $invoice->customer->phone }}</p>
                @endif
            </div>

            <table class="w-full text-left mb-8">
                <thead class="bg-gray-50 border-y border-gray-200">
                    <tr>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Item Description</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Qty</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Price</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($invoice->items as $item)
                        <tr>
                            <td class="py-4 px-4 text-sm text-gray-900">{{ $item->product->name }}</td>
                            <td class="py-4 px-4 text-sm text-gray-500 text-center">{{ $item->quantity }}</td>
                            <td class="py-4 px-4 text-sm text-gray-500 text-right">${{ number_format($item->price, 2) }}</td>
                            <td class="py-4 px-4 text-sm text-gray-900 font-medium text-right">${{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="flex justify-end">
                <div class="w-64">
                    <div class="flex justify-between py-2 border-t border-gray-200">
                        <span class="text-sm font-bold text-gray-900">Total Due:</span>
                        <span class="text-xl font-bold text-indigo-600">${{ number_format($invoice->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-16 pt-8 border-t border-gray-200 text-center text-sm text-gray-400">
                Thank you for your business!
            </div>

        </div>
    </div>
</x-app-layout>