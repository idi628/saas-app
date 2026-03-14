<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\TenantContext;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RequireModule
{
    public function handle(Request $request, Closure $next, string $moduleKey): Response
    {
        $tenantId = TenantContext::getId();
        
        // Check if this specific module is enabled for this tenant
        $isEnabled = DB::table('tenant_modules')
            ->where('tenant_id', $tenantId)
            ->where('module_key', $moduleKey)
            ->where('enabled', true)
            ->exists();

        if (!$isEnabled) {
            // If they haven't paid for it, return a 403 Forbidden error
            abort(403, 'Please upgrade your subscription to access this module.');
        }

        return $next($request);
    }
}