<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('tenant.team.index', ['tenant_slug' => auth()->user()->tenant->slug]) }}" class="text-gray-500 hover:text-gray-700">&larr; Back to Team</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight border-l pl-4 border-gray-300">
                {{ __('Manage Access:') }} <span class="text-indigo-600">{{ $user->name }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
            
            <div class="p-6 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Module Permissions</h3>
                    <p class="text-sm text-gray-500 mt-1">Select exactly what actions this employee is allowed to perform.</p>
                </div>
            </div>

            <form action="{{ route('tenant.team.permissions.update', ['tenant_slug' => auth()->user()->tenant->slug, 'user' => $user->id]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-700">
                        <thead class="text-xs uppercase bg-gray-100 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 font-bold">Module</th>
                                <th class="px-6 py-4 font-bold text-center border-l border-gray-200">View (Read)</th>
                                <th class="px-6 py-4 font-bold text-center border-l border-gray-200">Create</th>
                                <th class="px-6 py-4 font-bold text-center border-l border-gray-200">Edit (Update)</th>
                                <th class="px-6 py-4 font-bold text-center border-l border-gray-200 text-red-600">Delete</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            
                            @php
                                $modules = [
                                    'crm' => 'CRM (Customers)',
                                    'pos' => 'Point of Sale (Products)',
                                    'invoicing' => 'Invoicing'
                                ];
                            @endphp

                            @foreach($modules as $key => $label)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-bold text-gray-900">{{ $label }}</td>
                                    
                                    <td class="px-6 py-4 text-center border-l border-gray-100">
                                        <input type="checkbox" name="permissions[{{ $key }}][read]" value="1" {{ $user->hasPermission($key, 'read') ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                                    </td>
                                    
                                    <td class="px-6 py-4 text-center border-l border-gray-100">
                                        <input type="checkbox" name="permissions[{{ $key }}][create]" value="1" {{ $user->hasPermission($key, 'create') ? 'checked' : '' }} class="w-5 h-5 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 cursor-pointer">
                                    </td>
                                    
                                    <td class="px-6 py-4 text-center border-l border-gray-100">
                                        <input type="checkbox" name="permissions[{{ $key }}][update]" value="1" {{ $user->hasPermission($key, 'update') ? 'checked' : '' }} class="w-5 h-5 text-yellow-500 bg-gray-100 border-gray-300 rounded focus:ring-yellow-500 cursor-pointer">
                                    </td>
                                    
                                    <td class="px-6 py-4 text-center border-l border-gray-100 bg-red-50/30">
                                        <input type="checkbox" name="permissions[{{ $key }}][delete]" value="1" {{ $user->hasPermission($key, 'delete') ? 'checked' : '' }} class="w-5 h-5 text-red-600 bg-gray-100 border-red-300 rounded focus:ring-red-500 cursor-pointer">
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-gray-200 bg-gray-50 flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md font-bold hover:bg-indigo-700 transition-colors shadow-sm">
                        Save Access Matrix
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>