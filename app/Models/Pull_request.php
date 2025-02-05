<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pull_request extends Model
{
    protected $fillable = [
        'payment_way_id',
        'employee_id',
        'user_id',
        'total',
        'accepted',
        'blocked'
    ];
}
