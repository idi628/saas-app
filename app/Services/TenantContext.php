<?php

namespace App\Services;

class TenantContext
{
    protected static $tenantId;

    public static function setId($id) 
    { 
        self::$tenantId = $id; 
    }
    
    public static function getId() 
    { 
        return self::$tenantId; 
    }
}