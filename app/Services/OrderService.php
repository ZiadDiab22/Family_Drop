<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderService
{
  public function showUserOrders($id)
  {
    $data = Order::where('user_id', $id)
      ->join('users as u', 'u.id', 'user_id')
      ->join('order_states as os', 'os.id', 'state_id')
      ->join('addresses as ad', 'ad.id', 'addresse_id')
      ->get([
        'orders.id',
        'user_id',
        'u.name as user_name',
        'state_id',
        'os.name as state_name',
        'addresse_id',
        'ad.name as addresse',
        'title',
        'customer_name',
        'customer_number',
        'account_name',
        'total_price',
        'total_quantity',
        'total_profit',
        'orders.blocked',
        'orders.created_at',
        'orders.updated_at'
      ]);

    if ($data) {
      foreach ($data as $order) {
        $products = DB::table('order_products as op')->where('order_id', $order['id'])
          ->join('products as p', 'op.product_id', 'p.id')
          ->leftjoin('product_colors as pc', 'op.color_id', 'pc.id')
          ->leftjoin('product_sizes as ps', 'op.size_id', 'ps.id')
          ->leftjoin('colors as c', 'pc.color_id', 'c.id')
          ->leftjoin('sizes as s', 'ps.size_id', 's.id')
          ->get([
            'order_id',
            'op.product_id',
            'p.name as product_name',
            'p.disc',
            'cost_price',
            'images_array',
            'ps.size_id',
            's.name as size',
            'pc.color_id',
            'c.name as color',
            'code',
            'op.quantity',
            'op.selling_price',
            'profit'
          ]);
        $order['products'] = $products;
      }
    }

    return $data;
  }

  public function getUserFinishedOrders($id)
  {
    $data = Order::where('user_id', $id)->where('state_id', 6)
      ->join('users as u', 'u.id', 'user_id')
      ->join('order_states as os', 'os.id', 'state_id')
      ->join('addresses as ad', 'ad.id', 'addresse_id')
      ->get([
        'orders.id',
        'user_id',
        'u.name as user_name',
        'state_id',
        'os.name as state_name',
        'addresse_id',
        'ad.name as addresse',
        'title',
        'customer_name',
        'customer_number',
        'account_name',
        'total_price',
        'total_quantity',
        'total_profit',
        'orders.blocked',
        'orders.created_at',
        'orders.updated_at'
      ]);

    if ($data) {
      foreach ($data as $order) {
        $products = DB::table('order_products as op')->where('order_id', $order['id'])
          ->join('products as p', 'op.product_id', 'p.id')
          ->leftjoin('product_colors as pc', 'op.color_id', 'pc.id')
          ->leftjoin('product_sizes as ps', 'op.size_id', 'ps.id')
          ->leftjoin('colors as c', 'pc.color_id', 'c.id')
          ->leftjoin('sizes as s', 'ps.size_id', 's.id')
          ->get([
            'order_id',
            'op.product_id',
            'p.name as product_name',
            'p.disc',
            'cost_price',
            'images_array',
            'ps.size_id',
            's.name as size',
            'pc.color_id',
            'c.name as color',
            'code',
            'op.quantity',
            'op.selling_price',
            'profit'
          ]);
        $order['products'] = $products;
      }
    }
    return $data;
  }

  public function getTotalFinishedCancelledNewOrders($id)
  {
    $data = [];
    $data[] = DB::table('orders')
      ->where('user_id', $id)
      ->where('state_id', 6)
      ->count();

    $data[] = DB::table('orders')
      ->where('user_id', $id)
      ->where('state_id', 5)
      ->count();

    $data[] = DB::table('orders')
      ->where('user_id', $id)
      ->where('state_id', 1)
      ->count();

    return $data;
  }
}
