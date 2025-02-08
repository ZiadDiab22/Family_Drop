<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\address;
use App\Models\Order;
use App\Models\Order_product;
use App\Models\Order_tag;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public function addOrder(Request $request)
    {
        $validatedData = $request->validate([
            'addresse_id' => 'required',
            'customer_name' => 'required',
            'products' => 'required',
            'title' => '',
            'customer_number' => '',
            'account_name' => '',
        ]);

        if (!(address::where('id', $request->addresse_id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong addresse_id , not found',
            ]);
        }

        $validatedData['state_id'] = 1;
        $validatedData['user_id'] = Auth::user()->id;
        $validatedData['total_price'] = $validatedData['total_quantity'] = $validatedData['total_profit'] = 0;

        $order = Order::create($validatedData);

        $total_price = 0;
        $total_profit = 0;

        foreach ($request->products as $product) {
            $data = [];
            $pr = Product::find($product['id']);
            if ($product['quantity'] > $pr->quantity) {
                Order::where('id', $order->id)->delete();
                return response([
                    'status' => false,
                    'message' => 'we dont have enough quantity to product id = ' . $product['id'],
                ]);
            }
            $data['quantity'] = $product['quantity'];
            $pr->quantity -= $product['quantity'];
            $pr->save();
            $data['order_id'] = $order->id;
            $data['product_id'] = $product['id'];
            if ($product['size_id'] != 0) $data['size_id'] = $product['size_id'];
            if ($product['color_id'] != 0) $data['color_id'] = $product['color_id'];
            $data['selling_price'] = $product['price'];
            $data['profit'] = $product['price'] - $product['cost_price'];
            Order_product::create($data);
            $total_price += $product['quantity'] * $product['cost_price'];
            $total_profit += $product['quantity'] * $data['profit'];
        }

        $order->total_quantity = DB::table('order_products')->where('order_id', $order->id)->sum('quantity');
        $order->total_profit = $total_profit;
        $order->total_price = $total_price;
        $order->save();

        $data = $this->OrderService->showUserOrders(Auth::user()->id);

        return response()->json([
            'status' => true,
            'orders' => $data,
        ]);
    }
}
