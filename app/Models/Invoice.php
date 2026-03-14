<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Invoice extends Model
{
    use BelongsToTenant; // Secure this to the company!

    protected $fillable = [
        'customer_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'total_amount',
        'status',
    ];

    // An invoice belongs to a specific customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // An invoice has many line items
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}