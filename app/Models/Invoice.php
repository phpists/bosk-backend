<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'number',
        'provider_name',
        'provider_tax_id',
        'provider_lines',
        'issue_date',
        'due_date',
        'po_number',
        'subtotal',
        'tax',
        'total',
        'note',
        'customer_id',
        'status',
        'user_id'
    ];

    public function invoice_items() {
        return $this->hasMany(InvoiceItem::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }
}
