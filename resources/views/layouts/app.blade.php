<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 print:bg-white">
        <div class="min-h-screen bg-gray-50 flex print:bg-white print:block">
            
            <aside class="w-64 bg-white border-r border-gray-200 hidden sm:flex sm:flex-col justify-between print:hidden">
                <div>
                    <div class="h-16 flex items-center px-6 border-b border-gray-100 shrink-0">
                        <span class="text-xl font-bold text-indigo-600 truncate">
                            {{ auth()->user()->tenant ? auth()->user()->tenant->name : 'MySaaS' }}
                        </span>
                    </div>

                    <nav class="px-4 py-6 space-y-2 overflow-y-auto">
                        @if(auth()->user()->tenant)
                            <a href="{{ route('tenant.dashboard', ['tenant_slug' => auth()->user()->tenant->slug]) }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('tenant.dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-100' }}">
                                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('tenant.dashboard') ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                Dashboard
                            </a>

                            @if(auth()->user()->tenant->hasModule('crm'))
                                <a href="{{ route('tenant.customers.index', ['tenant_slug' => auth()->user()->tenant->slug]) }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('tenant.customers.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-100' }}">
                                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('tenant.customers.*') ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    CRM
                                </a>
                            @endif

                            @if(auth()->user()->tenant->hasModule('pos'))
                                <a href="{{ route('tenant.pos.index', ['tenant_slug' => auth()->user()->tenant->slug]) }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('tenant.pos.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-100' }}">
                                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('tenant.pos.*') ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    Point of Sale
                                </a>
                            @endif

                            @if(auth()->user()->tenant->hasModule('invoicing'))
                                <a href="{{ route('tenant.invoices.index', ['tenant_slug' => auth()->user()->tenant->slug]) }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('tenant.invoices.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-100' }}">
                                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('tenant.invoices.*') ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Invoicing
                                </a>
                            @endif
                        @else
                            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-100' }}">
                                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                Dashboard
                            </a>
                        @endif
                    </nav>
                </div>

                @if(auth()->user()->tenant)
                <div class="px-4 py-4 border-t border-gray-100 space-y-2">
                    
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.index') }}" class="flex items-center px-4 py-2.5 text-sm font-bold rounded-lg bg-gray-900 text-white hover:bg-gray-800 print:hidden">
                            <svg class="w-5 h-5 mr-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Super Admin
                        </a>
                    @endif

                    <a href="{{ route('tenant.team.index', ['tenant_slug' => auth()->user()->tenant->slug]) }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('tenant.team.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-100' }} print:hidden">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('tenant.team.*') ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Team
                    </a>

                    <a href="{{ route('tenant.settings.edit', ['tenant_slug' => auth()->user()->tenant->slug]) }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('tenant.settings.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-100' }} print:hidden">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('tenant.settings.*') ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Settings
                    </a>
                </div>
                @endif
            </aside>

            <div class="flex-1 flex flex-col min-w-0 print:block">
                
                <div class="print:hidden">
                    @include('layouts.navigation')
                </div>

                @isset($header)
                    <header class="bg-white shadow-sm border-b border-gray-200 print:hidden">
                        <div class="px-4 py-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main class="p-6 print:p-0">
                    <div class="print:hidden">
                        @if (session('success'))
                            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-90" class="mb-6 bg-green-50 border border-green-200 rounded-md p-4 flex justify-between items-center shadow-sm">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                    </div>
                                    <div class="ml-3"><p class="text-sm font-medium text-green-800">{{ session('success') }}</p></div>
                                </div>
                                <button @click="show = false" class="text-green-600 hover:text-green-800 focus:outline-none"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                            </div>
                        @endif
                    </div>
                    
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>