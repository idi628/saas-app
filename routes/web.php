<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TenantSettingsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// SUPER ADMIN ROUTES
Route::middleware(['auth', 'verified', \App\Http\Middleware\IsSuperAdmin::class])->prefix('admin')->group(function () {
    Route::get('/tenants', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/tenants/{tenant}/modules', [AdminController::class, 'updateModules'])->name('admin.tenants.modules');
});

// TENANT-SPECIFIC SAAS ROUTES
Route::middleware(['auth', 'verified', 'tenant'])->prefix('t/{tenant_slug}')->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('tenant.dashboard');

    // Modules
    Route::get('/crm/customers', [CustomerController::class, 'index'])->name('tenant.customers.index')->middleware('module:crm');
    Route::get('/crm/customers/create', [CustomerController::class, 'create'])->name('tenant.customers.create')->middleware('module:crm');
    Route::post('/crm/customers', [CustomerController::class, 'store'])->name('tenant.customers.store')->middleware('module:crm');
    Route::get('/crm/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('tenant.customers.edit')->middleware('module:crm');
    Route::put('/crm/customers/{customer}', [CustomerController::class, 'update'])->name('tenant.customers.update')->middleware('module:crm');
    Route::delete('/crm/customers/{customer}', [CustomerController::class, 'destroy'])->name('tenant.customers.destroy')->middleware('module:crm');

    Route::get('/pos/products', [ProductController::class, 'index'])->name('tenant.pos.index')->middleware('module:pos');
    Route::get('/pos/products/create', [ProductController::class, 'create'])->name('tenant.pos.create')->middleware('module:pos');
    Route::post('/pos/products', [ProductController::class, 'store'])->name('tenant.pos.store')->middleware('module:pos');
    Route::get('/pos/products/{product}/edit', [ProductController::class, 'edit'])->name('tenant.pos.edit')->middleware('module:pos');
    Route::put('/pos/products/{product}', [ProductController::class, 'update'])->name('tenant.pos.update')->middleware('module:pos');
    Route::delete('/pos/products/{product}', [ProductController::class, 'destroy'])->name('tenant.pos.destroy')->middleware('module:pos');

    Route::get('/invoices', [InvoiceController::class, 'index'])->name('tenant.invoices.index')->middleware('module:invoicing');
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('tenant.invoices.create')->middleware('module:invoicing');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('tenant.invoices.store')->middleware('module:invoicing');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('tenant.invoices.show')->middleware('module:invoicing');

    // Settings & Team
    Route::get('/settings', [TenantSettingsController::class, 'edit'])->name('tenant.settings.edit');
    Route::put('/settings', [TenantSettingsController::class, 'update'])->name('tenant.settings.update');
    
    // NEW: Team Management
    Route::get('/team', [TeamController::class, 'index'])->name('tenant.team.index');
    Route::post('/team', [TeamController::class, 'store'])->name('tenant.team.store');
    Route::delete('/team/{user}', [TeamController::class, 'destroy'])->name('tenant.team.destroy');

    // NEW: Manage Permissions
    Route::get('/team/{user}/permissions', [TeamController::class, 'editPermissions'])->name('tenant.team.permissions.edit');
    Route::put('/team/{user}/permissions', [TeamController::class, 'updatePermissions'])->name('tenant.team.permissions.update');
});

require __DIR__.'/auth.php';