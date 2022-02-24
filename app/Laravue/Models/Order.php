<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable =  [
        'user_id', 
        'customer_name',
        'phone',
        'product_id',
        'price',
        'quantity',
        'total',
        'discount',
        'address'
    ];
}
