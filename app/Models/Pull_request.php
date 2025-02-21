<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pull_request extends Model
{
    protected $fillable = [
        'payment_way_id',
        'employee_id',
        'user_id',
        'total',
        'payment_data',
        'accepted',
        'blocked'
    ];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }
}
