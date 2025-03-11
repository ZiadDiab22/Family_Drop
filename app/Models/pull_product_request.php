<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class pull_product_request extends Model
{
    protected $fillable = [
        'mercher_id',
        'employee_id',
        'product_id',
        'quantity',
        'accepted',
        'blocked'
    ];

    protected function imagesArray(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }
}
