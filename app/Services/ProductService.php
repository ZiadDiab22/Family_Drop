<?php


namespace App\Services;

use App\Models\Product;
use App\Models\product_color;
use App\Models\product_size;
use Illuminate\Support\Facades\DB;
use App\Models\Product_type;

class ProductService
{
  public function showProducts()
  {

    $data = Product::join('product_types as pt', 'pt.id', 'products.type_id')
      ->join('users as u', 'u.id', 'owner_id')
      ->join('user_types as t', 't.id', 'u.type_id')
      ->get([
        'products.id',
        'products.name',
        'disc',
        'long_disc',
        'products.type_id',
        'pt.name as type_name',
        'owner_id',
        'u.name as owner_name',
        'email as owner_email',
        'phone_no as owner_phone_no',
        'img_url as owner_img_url',
        'u.type_id as owner_type_id',
        't.name as owner_type',
        'images_array',
        'video_url',
        'cost_price',
        'selling_price',
        'quantity',
        'sales',
        'profit_rate',
        'products.blocked',
        'products.created_at',
        'products.updated_at'
      ]);

    foreach ($data as $p) {
      $sizes = product_size::where('product_id', $p['id'])
        ->join('sizes as s', 'size_id', 's.id')
        ->get(['s.id', 's.name']);

      $colors = product_color::where('product_id', $p['id'])
        ->join('colors as c', 'color_id', 'c.id')
        ->get(['c.id', 'c.name', 'code']);

      $p['sizes'] = $sizes;
      $p['colors'] = $colors;
    }

    return $data;
  }

  public function showProductInfo($id)
  {
    $data = Product::where('products.id', $id)->join('product_types as pt', 'pt.id', 'products.type_id')
      ->join('users as u', 'u.id', 'owner_id')
      ->join('user_types as t', 't.id', 'u.type_id')
      ->get([
        'products.id',
        'products.name',
        'disc',
        'long_disc',
        'products.type_id',
        'pt.name as type_name',
        'owner_id',
        'u.name as owner_name',
        'email as owner_email',
        'phone_no as owner_phone_no',
        'img_url as owner_img_url',
        'u.type_id as owner_type_id',
        't.name as owner_type',
        'images_array',
        'video_url',
        'cost_price',
        'selling_price',
        'quantity',
        'sales',
        'profit_rate',
        'products.blocked',
        'products.created_at',
        'products.updated_at'
      ]);

    foreach ($data as $p) {
      $sizes = product_size::where('product_id', $p['id'])
        ->join('sizes as s', 'size_id', 's.id')
        ->get(['product_sizes.id', 'size_id', 's.name as size_name']);

      $colors = product_color::where('product_id', $p['id'])
        ->join('colors as c', 'color_id', 'c.id')
        ->get(['product_colors.id', 'color_id', 'c.name as color_name', 'code']);

      $p['sizes'] = $sizes;
      $p['colors'] = $colors;
    }

    return $data;
  }

  public function showProductTypes()
  {
    $types = Product_type::get();

    return $types;
  }

  public function getMercherProducts($id)
  {
    $data = Product::where('owner_id', $id)->join('product_types as pt', 'pt.id', 'products.type_id')
      ->join('users as u', 'u.id', 'owner_id')
      ->join('user_types as t', 't.id', 'u.type_id')
      ->get([
        'products.id',
        'products.name',
        'disc',
        'long_disc',
        'products.type_id',
        'pt.name as type_name',
        'owner_id',
        'u.name as owner_name',
        'email as owner_email',
        'phone_no as owner_phone_no',
        'img_url as owner_img_url',
        'u.type_id as owner_type_id',
        't.name as owner_type',
        'images_array',
        'video_url',
        'cost_price',
        'selling_price',
        'quantity',
        'sales',
        'profit_rate',
        'products.blocked',
        'products.created_at',
        'products.updated_at'
      ]);

    foreach ($data as $p) {
      $sizes = product_size::where('product_id', $p['id'])
        ->join('sizes as s', 'size_id', 's.id')
        ->get(['s.id', 's.name']);

      $colors = product_color::where('product_id', $p['id'])
        ->join('colors as c', 'color_id', 'c.id')
        ->get(['c.id', 'c.name', 'code']);

      $p['sizes'] = $sizes;
      $p['colors'] = $colors;
    }

    return $data;
  }

  public function showAllProductsOrdered()
  {
    $data = product::where('products.blocked', 0)
      ->where('products.quantity', '>', 0)
      ->join('product_types as pt', 'pt.id', 'products.type_id')
      ->join('users as u', 'u.id', 'owner_id')
      ->join('user_types as t', 't.id', 'u.type_id')
      ->orderBy('sales', 'desc')
      ->get([
        'products.id',
        'products.name',
        'disc',
        'long_disc',
        'products.type_id',
        'pt.name as type_name',
        'owner_id',
        'u.name as owner_name',
        'email as owner_email',
        'phone_no as owner_phone_no',
        'img_url as owner_img_url',
        'u.type_id as owner_type_id',
        't.name as owner_type',
        'images_array',
        'video_url',
        'cost_price',
        'selling_price',
        'quantity',
        'sales',
        'profit_rate',
        'products.blocked',
        'products.created_at',
        'products.updated_at'
      ]);

    return $data;
  }

  public function showProductsOrdered($name)
  {
    $data = product::where('products.name', 'like', '%' . $name . '%')->where('products.blocked', 0)
      ->where('products.quantity', '>', 0)
      ->join('product_types as pt', 'pt.id', 'products.type_id')
      ->join('users as u', 'u.id', 'owner_id')
      ->join('user_types as t', 't.id', 'u.type_id')
      ->orderBy('sales', 'desc')
      ->get([
        'products.id',
        'products.name',
        'disc',
        'long_disc',
        'products.type_id',
        'pt.name as type_name',
        'owner_id',
        'u.name as owner_name',
        'email as owner_email',
        'phone_no as owner_phone_no',
        'img_url as owner_img_url',
        'u.type_id as owner_type_id',
        't.name as owner_type',
        'images_array',
        'video_url',
        'cost_price',
        'selling_price',
        'quantity',
        'sales',
        'profit_rate',
        'products.blocked',
        'products.created_at',
        'products.updated_at'
      ]);

    return $data;
  }

  public function getProductStats($date1, $date2)
  {
    $data =  DB::table('products')
      ->whereDate('products.created_at', '>=', $date1)
      ->whereDate('products.created_at', '<=', $date2)
      ->join('product_types as pt', 'pt.id', 'products.type_id')
      ->join('users as u', 'u.id', 'owner_id')
      ->join('user_types as t', 't.id', 'u.type_id')
      ->get([
        'products.id',
        'products.name',
        'disc',
        'long_disc',
        'products.type_id',
        'pt.name as type_name',
        'owner_id',
        'u.name as owner_name',
        'email as owner_email',
        'phone_no as owner_phone_no',
        'img_url as owner_img_url',
        'u.type_id as owner_type_id',
        't.name as owner_type',
        'images_array',
        'video_url',
        'cost_price',
        'selling_price',
        'quantity',
        'sales',
        'profit_rate',
        'products.blocked',
        'products.created_at',
        'products.updated_at'
      ]);

    foreach ($data as $p) {
      $sizes = product_size::where('product_id', $p->id)
        ->join('sizes as s', 'size_id', 's.id')
        ->get(['s.id', 's.name']);

      $colors = product_color::where('product_id', $p->id)
        ->join('colors as c', 'color_id', 'c.id')
        ->get(['c.id', 'c.name', 'code']);

      $p->sizes = $sizes;
      $p->colors = $colors;
    }

    return $data;
  }

  public function getTopProducts($date1, $date2)
  {
    $data = DB::table('orders as o')->where('state_id', 6)
      ->whereDate('o.created_at', '>=', $date1)
      ->whereDate('o.created_at', '<=', $date2)
      ->join('order_products as op', 'op.order_id', 'o.id')
      ->join('products as p', 'p.id', 'op.product_id')
      ->select('p.id', DB::raw('CAST(SUM(op.quantity) as UNSIGNED) as value'), 'p.name as label')
      ->groupBy('p.id', 'p.name')
      ->orderBy('value', 'desc')
      ->get();

    return $data;
  }
}
