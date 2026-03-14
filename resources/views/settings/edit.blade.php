<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Company Settings') }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            
            <form action="{{ route('tenant.settings.update', ['tenant_slug' => auth()->user()->tenant->slug]) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Company Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $tenant->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <p class="mt-2 text-sm text-gray-500">This name will appear on all your generated invoices and your dashboard.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Company URL Slug</label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                            {{ config('app.url') }}/t/
                        </span>
                        <input type="text" value="{{ $tenant->slug }}" disabled class="flex-1 min-w-0 block w-full px-3 rounded-none rounded-r-md border-gray-300 bg-gray-100 text-gray-500 sm:text-sm cursor-not-allowed">
                    </div>
                    <p class="mt-2 text-sm text-gray-400">Your URL slug is permanently locked and cannot be changed.</p>
                </div>

                <div class="flex justify-end border-t border-gray-200 pt-4">
                    <button type="submit" class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save Settings
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>