<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('CRM: Customers') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-medium text-gray-900">Customer List</h3>
            
            @if(auth()->user()->hasPermission('crm', 'create'))
                <a href="{{ route('tenant.customers.create', ['tenant_slug' => auth()->user()->tenant->slug]) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-sm font-medium transition-colors shadow-sm">
                    + Add Customer
                </a>
            @endif
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-bold">Name</th>
                        <th scope="col" class="px-6 py-4 font-bold">Company</th>
                        <th scope="col" class="px-6 py-4 font-bold">Email</th>
                        <th scope="col" class="px-6 py-4 font-bold">Phone</th>
                        <th scope="col" class="px-6 py-4 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($customers as $customer)
                        <tr class="bg-white hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $customer->name }}</td>
                            <td class="px-6 py-4">{{ $customer->company_name ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $customer->email ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $customer->phone ?? '-' }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end space-x-3">
                                    
                                    @if(auth()->user()->hasPermission('crm', 'update'))
                                        <a href="{{ route('tenant.customers.edit', ['tenant_slug' => auth()->user()->tenant->slug, 'customer' => $customer->id]) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>
                                    @endif

                                    @if(auth()->user()->hasPermission('crm', 'delete'))
                                        <form action="{{ route('tenant.customers.destroy', ['tenant_slug' => auth()->user()->tenant->slug, 'customer' => $customer->id]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this customer?');">
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
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                No customers found. 
                                @if(auth()->user()->hasPermission('crm', 'create'))
                                    Click "+ Add Customer" to get started!
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>