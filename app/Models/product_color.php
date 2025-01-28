<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class product_color extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'color_id',
        'product_id'
    ];
}
