<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Order_tag;
use Illuminate\Support\Facades\DB;

class OrderService
{
  public function showUserOrders($id)
  {
    $data = Order::where('user_id', $id)
      ->join('users as u', 'u.id', 'user_id')
      ->join('order_states as os', 'os.id', 'state_id')
      ->join('addresses as ad', 'ad.id', 'addresse_id')
      ->join('countries as c', 'u.country_id', 'c.id')
      ->join('user_types as ut', 'ut.id', 'u.type_id')
      ->join('cities as ci', 'ci.id', 'ad.city_id')
      ->join('countries as cc', 'cc.id', 'ci.country_id')
      ->orderBy('id', 'asc')
      ->get([
        'orders.id',
        'user_id',
        'u.name as user_name',
        'u.email',
        'u.phone_no',
        'u.country_id as user_country_id',
        'c.name as user_country',
        'u.type_id as user_type_id',
        'ut.name as user_type',
        'state_id',
        'os.name as state_name',
        'addresse_id',
        'ad.name as addresse',
        'ad.city_id',
        'ci.name as city',
        'ci.country_id',
        'cc.name as country',
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
        $tags = Order_tag::where('order_id', $order['id'])
          ->join('users as u', 'u.id', 'user_id')
          ->get([
            'order_tags.id',
            'order_id',
            'user_id',
            'u.name',
            'email',
            'phone_no',
            'img_url',
            'text',
            'order_tags.created_at',
            'order_tags.updated_at'
          ]);

        $order['products'] = $products;
        $order['tags'] = $tags;
      }
    }

    return $data;
  }

  public function getOrders()
  {
    $data = Order::join('users as u', 'u.id', 'user_id')
      ->join('order_states as os', 'os.id', 'state_id')
      ->join('addresses as ad', 'ad.id', 'addresse_id')
      ->join('countries as c', 'u.country_id', 'c.id')
      ->join('user_types as ut', 'ut.id', 'u.type_id')
      ->join('cities as ci', 'ci.id', 'ad.city_id')
      ->join('countries as cc', 'cc.id', 'ci.country_id')
      ->orderBy('id', 'asc')
      ->get([
        'orders.id',
        'user_id',
        'u.name as user_name',
        'u.email',
        'u.phone_no',
        'u.country_id as user_country_id',
        'c.name as user_country',
        'u.type_id as user_type_id',
        'ut.name as user_type',
        'state_id',
        'os.name as state_name',
        'addresse_id',
        'ad.name as addresse',
        'ad.city_id',
        'ci.name as city',
        'ci.country_id',
        'cc.name as country',
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
        $tags = Order_tag::where('order_id', $order['id'])
          ->join('users as u', 'u.id', 'user_id')
          ->get([
            'order_tags.id',
            'order_id',
            'user_id',
            'u.name',
            'email',
            'phone_no',
            'img_url',
            'text',
            'order_tags.created_at',
            'order_tags.updated_at'
          ]);

        $order['products'] = $products;
        $order['tags'] = $tags;
      }
    }

    return $data;
  }

  public function getOrderInfo($id)
  {
    $data = Order::where('orders.id', $id)
      ->join('users as u', 'u.id', 'user_id')
      ->join('order_states as os', 'os.id', 'state_id')
      ->join('addresses as ad', 'ad.id', 'addresse_id')
      ->join('countries as c', 'u.country_id', 'c.id')
      ->join('user_types as ut', 'ut.id', 'u.type_id')
      ->join('cities as ci', 'ci.id', 'ad.city_id')
      ->join('countries as cc', 'cc.id', 'ci.country_id')
      ->orderBy('id', 'asc')
      ->get([
        'orders.id',
        'user_id',
        'u.name as user_name',
        'u.email',
        'u.phone_no',
        'u.country_id as user_country_id',
        'c.name as user_country',
        'u.type_id as user_type_id',
        'ut.name as user_type',
        'state_id',
        'os.name as state_name',
        'addresse_id',
        'ad.name as addresse',
        'ad.city_id',
        'ci.name as city',
        'ci.country_id',
        'cc.name as country',
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
        $tags = Order_tag::where('order_id', $order['id'])
          ->join('users as u', 'u.id', 'user_id')
          ->get([
            'order_tags.id',
            'order_id',
            'user_id',
            'u.name',
            'email',
            'phone_no',
            'img_url',
            'text',
            'order_tags.created_at',
            'order_tags.updated_at'
          ]);

        $order['products'] = $products;
        $order['tags'] = $tags;
      }
    }

    return $data;
  }

  public function getUserFinishedOrders($id)
  {
    $data = Order::where('user_id', $id)
      ->join('users as u', 'u.id', 'user_id')
      ->join('order_states as os', 'os.id', 'state_id')
      ->join('addresses as ad', 'ad.id', 'addresse_id')
      ->join('countries as c', 'u.country_id', 'c.id')
      ->join('user_types as ut', 'ut.id', 'u.type_id')
      ->join('cities as ci', 'ci.id', 'ad.city_id')
      ->join('countries as cc', 'cc.id', 'ci.country_id')
      ->orderBy('id', 'asc')
      ->get([
        'orders.id',
        'user_id',
        'u.name as user_name',
        'u.email',
        'u.phone_no',
        'u.country_id as user_country_id',
        'c.name as user_country',
        'u.type_id as user_type_id',
        'ut.name as user_type',
        'state_id',
        'os.name as state_name',
        'addresse_id',
        'ad.name as addresse',
        'ad.city_id',
        'ci.name as city',
        'ci.country_id',
        'cc.name as country',
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
        $tags = Order_tag::where('order_id', $order['id'])
          ->join('users as u', 'u.id', 'user_id')
          ->get([
            'order_tags.id',
            'order_id',
            'user_id',
            'u.name',
            'email',
            'phone_no',
            'img_url',
            'text',
            'order_tags.created_at',
            'order_tags.updated_at'
          ]);

        $order['products'] = $products;
        $order['tags'] = $tags;
      }
    }
    return $data;
  }

  public function getTotalFinishedCancelledNewOrders($id)
  {
    $data = [];

    $totalOrders = DB::table('orders')
      ->where('user_id', $id)
      ->count();

    $data[] = $totalOrders;

    if ($totalOrders > 0) {
      for ($i = 1; $i < 7; $i++) {
        $stateCount = DB::table('orders')
          ->where('user_id', $id)
          ->where('state_id', $i)
          ->count();

        $percentage = round(($stateCount / $totalOrders) * 100, 2);
        $data[] = $percentage . "%";
      }
    } else array_push($data, '0%', '0%', '0%', '0%', '0%', '0%');

    return $data;
  }
}
