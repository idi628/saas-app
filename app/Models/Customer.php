<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant; // Pull in our custom security trait

class Customer extends Model
{
    // Activate the security lock!
    use BelongsToTenant;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company_name',
        'notes',
    ];

    // Note: We don't put 'tenant_id' in fillable because our BelongsToTenant trait 
    // magically injects it behind the scenes every time we create a new customer!
}