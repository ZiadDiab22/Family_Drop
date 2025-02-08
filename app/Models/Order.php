<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'state_id',
        'addresse_id',
        'title',
        'customer_name',
        'customer_number',
        'account_name',
        'total_price',
        'total_quantity',
        'total_profit',
        'blocked'
    ];
}
