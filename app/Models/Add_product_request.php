<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Add_product_request extends Model
{
    protected function imagesArray(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }

    protected $fillable = [
        'user_id',
        'employee_id',
        'product_name',
        'product_quantity',
        'product_price',
        'product_disc',
        'product_place',
        'accepted',
        'images_array',
        'blocked',
    ];
}
