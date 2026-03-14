<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Invoice') }}
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6" x-data="invoiceForm()">
            
            <form action="{{ route('tenant.invoices.store', ['tenant_slug' => auth()->user()->tenant->slug]) }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 bg-gray-50 p-4 rounded-md border border-gray-200">
                    <div>
                        <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer</label>
                        <select name="customer_id" id="customer_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">-- Select Customer --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="invoice_number" class="block text-sm font-medium text-gray-700">Invoice Number</label>
                        <input type="text" name="invoice_number" id="invoice_number" value="{{ $invoiceNumber }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 text-gray-500 sm:text-sm" readonly>
                    </div>
                    <div>
                        <label for="invoice_date" class="block text-sm font-medium text-gray-700">Invoice Date</label>
                        <input type="date" name="invoice_date" id="invoice_date" value="{{ date('Y-m-d') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                        <input type="date" name="due_date" id="due_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>

                <hr class="border-gray-200">

                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Line Items</h3>
                    
                    <template x-for="(item, index) in items" :key="item.id">
                        <div class="flex items-center space-x-4 mb-4">
                            
                            <div class="flex-1">
                                <select :name="`items[${index}][product_id]`" x-model="item.product_id" @change="updatePrice(item)" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">-- Select Product --</option>
                                    <template x-for="product in products" :key="product.id">
                                        <option :value="product.id" x-text="product.name + ' (Stock: ' + product.stock_quantity + ')'"></option>
                                    </template>
                                </select>
                            </div>

                            <div class="w-24">
                                <input type="number" :name="`items[${index}][quantity]`" x-model="item.quantity" min="1" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-center" placeholder="Qty">
                            </div>

                            <div class="w-32 text-right">
                                <span class="text-sm text-gray-500" x-text="'$' + item.price.toFixed(2)"></span>
                            </div>

                            <div class="w-32 text-right font-medium text-gray-900">
                                <span x-text="'$' + (item.price * item.quantity).toFixed(2)"></span>
                            </div>

                            <div class="w-10 text-right">
                                <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-700 focus:outline-none">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    </template>

                    <button type="button" @click="addItem()" class="mt-2 text-sm font-medium text-indigo-600 hover:text-indigo-500">
                        + Add another line
                    </button>
                </div>

                <div class="flex justify-end border-t border-gray-200 pt-4">
                    <div class="text-right">
                        <span class="text-sm text-gray-500 uppercase tracking-wide">Total Amount</span>
                        <div class="text-3xl font-bold text-gray-900" x-text="'$' + invoiceTotal"></div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-6">
                    <a href="{{ route('tenant.invoices.index', ['tenant_slug' => auth()->user()->tenant->slug]) }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </a>
                    <button type="submit" class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Generate Invoice
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function invoiceForm() {
            return {
                products: @json($products),
                items: [
                    { id: Date.now(), product_id: '', quantity: 1, price: 0 }
                ],
                
                updatePrice(item) {
                    let product = this.products.find(p => p.id == item.product_id);
                    item.price = product ? parseFloat(product.price) : 0;
                },
                
                addItem() {
                    this.items.push({ id: Date.now(), product_id: '', quantity: 1, price: 0 });
                },
                
                removeItem(index) {
                    if(this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                },
                
                get invoiceTotal() {
                    let total = 0;
                    this.items.forEach(item => {
                        total += (item.price * item.quantity);
                    });
                    return total.toFixed(2);
                }
            }
        }
    </script>
</x-app-layout>