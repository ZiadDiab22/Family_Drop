<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
