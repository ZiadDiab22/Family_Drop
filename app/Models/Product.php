<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'type_id',
        'disc',
        'long_disc',
        'cost_price',
        'quantity',
        'sales',
        'selling_price',
        'profit_rate',
        'images_array',
        'video_url',
        'blocked',
        'owner_id'
    ];
    protected function imagesArray(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }
}
