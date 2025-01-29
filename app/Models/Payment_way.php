<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment_way extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name','data','available'
    ];
}
