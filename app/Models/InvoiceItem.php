<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'product_id',
        'quantity',
        'price',
    ];

    // A line item belongs to an invoice
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    // A line item is linked to a specific product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}