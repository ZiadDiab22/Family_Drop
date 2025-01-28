<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class product_size extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'size_id',
        'product_id'
    ];
}
