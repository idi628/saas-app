<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\TenantContext;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        // Get the company slug from the URL: /t/{tenant_slug}/...
        $slug = $request->route('tenant_slug');

        // Look up the tenant in the database
        $tenant = DB::table('tenants')->where('slug', $slug)->first();

        if (!$tenant) {
            abort(404, 'Tenant not found.');
        }

        if ($tenant->status !== 'active') {
            abort(403, 'This account is currently suspended.');
        }

        // Lock the tenant ID into our global context
        TenantContext::setId($tenant->id);

        // Remove the slug from the request so we don't have to pass it to every single controller
        $request->route()->forgetParameter('tenant_slug');

        return $next($request);
    }
}