<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable =  [
        'user_id', 
        'customer_name',
        'phone',
        'address',
        'total',
        'discount',
        'due',
        'status',
        'paid'
    ];
}
