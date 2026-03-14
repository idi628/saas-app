<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Invoices') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-medium text-gray-900">Invoice History</h3>
            
            @if(auth()->user()->hasPermission('invoicing', 'create'))
                <a href="{{ route('tenant.invoices.create', ['tenant_slug' => auth()->user()->tenant->slug]) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-sm font-medium transition-colors shadow-sm">
                    + Create Invoice
                </a>
            @endif
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-bold">Invoice #</th>
                        <th scope="col" class="px-6 py-4 font-bold">Customer</th>
                        <th scope="col" class="px-6 py-4 font-bold">Date</th>
                        <th scope="col" class="px-6 py-4 font-bold">Total</th>
                        <th scope="col" class="px-6 py-4 font-bold">Status</th>
                        <th scope="col" class="px-6 py-4 font-bold text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($invoices as $invoice)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-bold text-indigo-600">#{{ $invoice->invoice_number }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $invoice->customer->name }}</td>
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}</td>
                            <td class="px-6 py-4 font-bold text-gray-900">${{ number_format($invoice->total_amount, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold uppercase tracking-wider">
                                    {{ $invoice->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('tenant.invoices.show', ['tenant_slug' => auth()->user()->tenant->slug, 'invoice' => $invoice->id]) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                No invoices found. 
                                @if(auth()->user()->hasPermission('invoicing', 'create'))
                                    Click "+ Create Invoice" to bill your first customer!
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>