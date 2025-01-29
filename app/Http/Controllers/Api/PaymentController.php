<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment_way;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function addPaymentWay(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'data' => 'required',
        ]);

        payment_way::create($validatedData);
        $data = payment_way::get();

        return response([
            'status' => true,
            'message' => 'Added Successfully',
            'data' => $data
        ]);
    }

    public function editPaymentWay(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        if (!(payment_way::where('id', $request->id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        $link = payment_way::find($request->id);

        if ($request->has('name')) $link->name = $request->name;
        if ($request->has('data')) $link->data = $request->data;

        $link->save();

        $data = payment_way::get();

        return response([
            'status' => true,
            'message' => 'edited Successfully',
            'data' => $data
        ]);
    }

    public function showPaymentWays()
    {
        $data = Payment_way::get();
        return response([
            'status' => true,
            'data' => $data
        ]);
    }
}
