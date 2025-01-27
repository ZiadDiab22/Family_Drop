<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_tag extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'user_id',
        'text'
    ];
}
