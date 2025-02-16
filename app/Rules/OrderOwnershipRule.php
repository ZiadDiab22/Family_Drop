<?php

namespace App\Rules;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Rule;

class OrderOwnershipRule implements Rule
{
    public function passes($attribute, $value)
    {
        if (Auth::user()->type_id < 3) return true;
        else return Order::where('id', $value)->where('user_id', Auth::user()->id)->exists();
    }

    public function message()
    {
        return 'You are not authorized to modify this order.';
    }
}
