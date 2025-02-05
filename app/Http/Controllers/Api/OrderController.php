<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Order_tag;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $OrderService;

    public function __construct(OrderService $OrderService)
    {
        $this->OrderService = $OrderService;
    }

    public function addOrderTag(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required',
            'text' => 'required',
        ]);

        if (!(Order::where('id', $request->order_id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , order not found',
            ]);
        }

        $validatedData['user_id'] = Auth::user()->id;

        Order_tag::create($validatedData);
        $data = Order_tag::where('order_id', $request->order_id)->get();

        return response([
            'status' => true,
            'message' => 'Added Successfully',
            'data' => $data
        ]);
    }

    public function showUserOrders()
    {
        $data = $this->OrderService->showUserOrders(Auth::user()->id);

        return response()->json([
            'status' => true,
            'orders' => $data,
        ]);
    }
}
