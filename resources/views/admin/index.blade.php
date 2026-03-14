<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Super Admin: Manage Tenants') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            
            <div class="p-6 border-b border-gray-200 bg-gray-900 text-white flex justify-between items-center">
                <h3 class="text-lg font-bold">Platform Companies</h3>
                <span class="px-3 py-1 bg-indigo-500 text-white rounded-full text-xs font-bold uppercase tracking-wider">Owner Mode Active</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4">Company Name</th>
                            <th class="px-6 py-4">URL Slug</th>
                            <th class="px-6 py-4">Manage Account & Access</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($tenants as $tenant)
                            <tr class="bg-white hover:bg-gray-50">
                                <td class="px-6 py-4 font-bold text-gray-900">{{ $tenant->name }}</td>
                                <td class="px-6 py-4 text-indigo-600 font-mono text-xs">/t/{{ $tenant->slug }}</td>
                                <td class="px-6 py-4">
                                    
                                    <form action="{{ route('admin.tenants.modules', $tenant->id) }}" method="POST" class="flex items-center space-x-6">
                                        @csrf
                                        
                                        <div class="flex items-center space-x-2 border-r border-gray-200 pr-6">
                                            <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Seat Limit:</label>
                                            <input type="number" name="max_users" value="{{ $tenant->max_users }}" min="1" required class="w-20 text-sm rounded-md border-gray-300 focus:ring-indigo-500 shadow-sm py-1.5 px-3">
                                        </div>

                                        <div class="flex items-center space-x-4">
                                            <label class="flex items-center space-x-2 cursor-pointer">
                                                <input type="checkbox" name="modules[]" value="crm" {{ $tenant->hasModule('crm') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                <span class="text-gray-700 font-medium text-sm">CRM</span>
                                            </label>

                                            <label class="flex items-center space-x-2 cursor-pointer">
                                                <input type="checkbox" name="modules[]" value="pos" {{ $tenant->hasModule('pos') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                <span class="text-gray-700 font-medium text-sm">POS</span>
                                            </label>

                                            <label class="flex items-center space-x-2 cursor-pointer">
                                                <input type="checkbox" name="modules[]" value="invoicing" {{ $tenant->hasModule('invoicing') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                <span class="text-gray-700 font-medium text-sm">Invoicing</span>
                                            </label>
                                        </div>

                                        <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-md text-xs font-bold hover:bg-gray-700 transition-colors shadow-sm">
                                            Save Setup
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>