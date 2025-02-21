<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class address extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'city_id',
        'delivery_price',
        'blocked'
    ];
}
