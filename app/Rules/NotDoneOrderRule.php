<?php

namespace App\Rules;

use App\Models\Order;
use Illuminate\Contracts\Validation\Rule;

class NotDoneOrderRule implements Rule
{
    public function passes($attribute, $value)
    {
        return Order::where('id', $value)->where('state_id', '!=', 6)->exists();
    }

    public function message()
    {
        return 'Order state should not be : done';
    }
}
