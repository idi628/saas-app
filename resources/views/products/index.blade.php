<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Point of Sale: Inventory') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-medium text-gray-900">Product List</h3>
            
            @if(auth()->user()->hasPermission('pos', 'create'))
                <a href="{{ route('tenant.pos.create', ['tenant_slug' => auth()->user()->tenant->slug]) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-sm font-medium transition-colors shadow-sm">
                    + Add Product
                </a>
            @endif
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-bold">Name</th>
                        <th scope="col" class="px-6 py-4 font-bold">Price</th>
                        <th scope="col" class="px-6 py-4 font-bold">Stock</th>
                        <th scope="col" class="px-6 py-4 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($products as $product)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $product->name }}</td>
                            <td class="px-6 py-4 font-bold text-indigo-600">${{ number_format($product->price, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 {{ $product->stock_quantity <= 5 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }} rounded-full text-xs font-bold">
                                    {{ $product->stock_quantity }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end space-x-3">
                                    
                                    @if(auth()->user()->hasPermission('pos', 'update'))
                                        <a href="{{ route('tenant.pos.edit', ['tenant_slug' => auth()->user()->tenant->slug, 'product' => $product->id]) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>
                                    @endif

                                    @if(auth()->user()->hasPermission('pos', 'delete'))
                                        <form action="{{ route('tenant.pos.destroy', ['tenant_slug' => auth()->user()->tenant->slug, 'product' => $product->id]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 font-medium">Delete</button>
                                        </form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                No products found. 
                                @if(auth()->user()->hasPermission('pos', 'create'))
                                    Click "+ Add Product" to build your inventory!
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>