<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class color extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'code'
    ];
}
