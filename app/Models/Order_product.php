<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    protected function imagesArray(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }
}
