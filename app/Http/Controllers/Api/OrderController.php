<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CancelOrderRequest;
use App\Http\Requests\DeliveringOrderRequest;
use App\Http\Requests\DoneOrderRequest;
use App\Http\Requests\EndingOrderRequest;
use App\Models\address;
use App\Models\Order;
use App\Models\Order_product;
use App\Models\Order_tag;
use App\Models\Product;
use App\Models\User;
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

    public function showOrders()
    {
        $data = $this->OrderService->getOrders();

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
            $total_price += $product['quantity'] * $product['price'];
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

    public function cancelOrder(CancelOrderRequest $request)
    {
        $order = Order::find($request->id);
        $order->state_id = 5;
        $order->save();

        $products = Order_product::where('order_id', $request->id)->get();
        foreach ($products as $product) {
            if (Product::where('id', $product['product_id'])->exists()) {
                $p = Product::find($product['product_id']);
                $p->quantity += $product['quantity'];
                $p->save();
            }
        }

        if (Auth::user()->type_id == 4)
            $data = $this->OrderService->showUserOrders(Auth::user()->id);
        else $data = $this->OrderService->getOrders();

        return response()->json([
            'status' => true,
            'orders' => $data
        ]);
    }

    public function startWorkingOrder(CancelOrderRequest $request)
    {
        $order = Order::find($request->id);
        $order->state_id = 2;
        $order->save();

        $data = $this->OrderService->getOrders();

        return response()->json([
            'status' => true,
            'orders' => $data
        ]);
    }

    public function endingOrder(EndingOrderRequest $request)
    {
        $order = Order::find($request->id);
        $order->state_id = 3;
        $order->save();

        $data = $this->OrderService->getOrders();

        return response()->json([
            'status' => true,
            'orders' => $data
        ]);
    }

    public function deliveringOrder(DeliveringOrderRequest $request)
    {
        $order = Order::find($request->id);
        $order->state_id = 4;
        $order->save();

        $data = $this->OrderService->getOrders();

        return response()->json([
            'status' => true,
            'orders' => $data
        ]);
    }

    public function doneOrder(DoneOrderRequest $request)
    {
        $order = Order::find($request->id);
        $order->state_id = 6;
        $order->save();

        // Assigning the order products as "sales" in products table
        // Transferring the cost price of products to product owners
        $total_cost = 0;
        $products = Order_product::where('order_id', $request->id)->get();
        foreach ($products as $product) {
            if (Product::where('id', $product['product_id'])->exists()) {
                $p = Product::find($product['product_id']);
                $p->sales += $product['quantity'];
                $p->save();
                $owner = User::find($p->owner_id);
                $product_profit = $p->cost_price * $product['quantity'];
                $owner->badget += $product_profit;
                $owner->save();
                $total_cost += $product_profit;
            }
        }

        // Transferring a percentage of profits to the marketer
        $marketer_profit = 0;
        $user = User::find($order->user_id);
        $value = DB::table('settings')->value('value');
        $marketer_profit = ($value * $order->total_profit) / 100;
        $user->badget += $marketer_profit;
        $user->save();

        // Transferring a platform_profits

        $platform_profits = $order->total_price - $marketer_profit - $total_cost;

        if (!(DB::table('settings')->where('name', 'platform profits')->exists())) {
            DB::table('settings')->insert([
                'name' => 'platform profits',
                'value' => $platform_profits
            ]);
        } else {
            DB::table('settings')
                ->where('name', 'platform profits')
                ->increment('value', $platform_profits);
        }

        $data = $this->OrderService->getOrders();

        return response()->json([
            'status' => true,
            'orders' => $data
        ]);
    }
}
