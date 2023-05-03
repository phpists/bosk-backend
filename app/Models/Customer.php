<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'client_name',
        'phone',
        'email',
        'first_name',
        'last_name',
        'currency',
        'address_1',
        'address_2',
        'city',
        'postal_code',
        'country',
        'province',
        'website',
        'notes',
        'user_id'
    ];
}
