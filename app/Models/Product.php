<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Product extends Model
{
    // Activate the security lock!
    use BelongsToTenant;

    protected $fillable = [
        'name',
        'sku',
        'price',
        'stock_quantity',
    ];
}