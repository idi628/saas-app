<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Team Directory') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="mb-6 bg-white shadow-sm sm:rounded-lg p-4 border border-gray-100 flex items-center justify-between">
            <div>
                <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Plan Usage</h4>
                <p class="text-sm text-gray-500">You are using {{ $currentCount }} out of {{ $maxUsers }} available seats.</p>
            </div>
            
            <div class="w-1/3 bg-gray-200 rounded-full h-2.5">
                <div class="{{ $currentCount >= $maxUsers ? 'bg-red-600' : 'bg-indigo-600' }} h-2.5 rounded-full" style="width: {{ $maxUsers > 0 ? min(($currentCount / $maxUsers) * 100, 100) : 100 }}%"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="md:col-span-1">
                <div class="bg-white shadow-sm sm:rounded-lg p-6 border border-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Add Team Member</h3>
                    
                    @if($maxUsers > 0 && $currentCount < $maxUsers)
                        <p class="text-sm text-gray-500 mb-6">Create an account for an employee so they can access this workspace.</p>
                        
                        <form action="{{ route('tenant.team.store', ['tenant_slug' => auth()->user()->tenant->slug]) }}" method="POST" class="space-y-4">
                            @csrf
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email Address</label>
                                <input type="email" name="email" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Temporary Password</label>
                                <input type="password" name="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md shadow-sm text-sm font-medium hover:bg-indigo-700 transition-colors">
                                Create Account
                            </button>
                        </form>
                    @else
                        <div class="bg-red-50 border border-red-200 rounded-md p-4 text-center">
                            <svg class="mx-auto h-8 w-8 text-red-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <h4 class="text-sm font-bold text-red-800 mb-1">Maximum Seats Reached</h4>
                            <p class="text-xs text-red-600 mb-4">You have reached your limit of {{ $maxUsers }} team members.</p>
                            <button class="w-full bg-red-600 text-white py-2 px-4 rounded-md shadow-sm text-sm font-bold hover:bg-red-700 transition-colors">
                                Upgrade Plan
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <div class="md:col-span-2">
                <div class="bg-white shadow-sm sm:rounded-lg border border-gray-100 overflow-hidden">
                    <div class="p-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-800">Current Members</h3>
                    </div>
                    
                    <ul class="divide-y divide-gray-100">
                        @foreach($teamMembers as $member)
                            <li class="p-4 flex items-center justify-between hover:bg-gray-50">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-lg mr-4">
                                        {{ substr($member->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">{{ $member->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $member->email }}</p>
                                    </div>
                                </div>
                                
                                @if($member->is_tenant_owner)
                                    <span class="text-xs font-bold text-green-600 uppercase tracking-wider bg-green-50 px-2 py-1 rounded-full">Owner</span>
                                @else
                                    @if(auth()->user()->is_tenant_owner)
                                        <div class="flex items-center space-x-4">
                                            <a href="{{ route('tenant.team.permissions.edit', ['tenant_slug' => auth()->user()->tenant->slug, 'user' => $member->id]) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Permissions</a>
                                            
                                            <form action="{{ route('tenant.team.destroy', ['tenant_slug' => auth()->user()->tenant->slug, 'user' => $member->id]) }}" method="POST" onsubmit="return confirm('Revoke access for this user?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">Remove</button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 uppercase tracking-wider">Employee</span>
                                    @endif
                                @endif

                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>