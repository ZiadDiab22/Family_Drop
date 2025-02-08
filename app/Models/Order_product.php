<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_product extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'product_id',
        'size_id',
        'color_id',
        'quantity',
        'selling_price',
        'profit'
    ];
}
