<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'max_users', // NEW: Added to allow saving the limit
    ];

    // A tenant has many users
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Helper to check if a tenant has access to a specific module
    public function hasModule($moduleKey)
    {
        return DB::table('tenant_modules')
            ->where('tenant_id', $this->id)
            ->where('module_key', $moduleKey)
            ->where('enabled', true)
            ->exists();
    }
}