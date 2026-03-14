<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'tenant_id',
        'is_admin',
        'is_tenant_owner', // NEW
        'permissions',     // NEW
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_tenant_owner' => 'boolean',
            'permissions' => 'array', // Magically casts JSON to an array!
        ];
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    // THE MAGIC PERMISSION HELPER
    public function hasPermission($module, $action)
    {
        // 1. Super Admins and Company Owners can do everything automatically
        if ($this->is_admin || $this->is_tenant_owner) {
            return true;
        }

        // 2. Otherwise, check the employee's specific JSON permission matrix
        $perms = $this->permissions ?? [];
        
        // Return true if the specific checkbox was ticked!
        return isset($perms[$module][$action]) && $perms[$module][$action] === '1';
    }
}