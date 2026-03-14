<?php

namespace App\Traits;

use App\Services\TenantContext;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    protected static function booted()
    {
        // Auto-inject tenant_id when creating new records
        static::creating(function ($model) {
            if (TenantContext::getId()) {
                $model->tenant_id = TenantContext::getId();
            }
        });

        // Hard-filter all queries by the current tenant
        static::addGlobalScope('tenant', function (Builder $builder) {
            if (TenantContext::getId()) {
                $builder->where('tenant_id', TenantContext::getId());
            }
        });
    }
}