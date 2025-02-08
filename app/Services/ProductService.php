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
      ->get([
        'products.id',
        'products.name',
        'disc',
        'long_disc',
        'products.type_id',
        'pt.name as type_name',
        'owner_id',
        'u.name as owner_name',
        'images_array',
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

  public function showProductInfo($id)
  {
    $data = Product::where('products.id', $id)->join('product_types as pt', 'pt.id', 'products.type_id')
      ->join('users as u', 'u.id', 'owner_id')
      ->get([
        'products.id',
        'products.name',
        'disc',
        'long_disc',
        'products.type_id',
        'pt.name as type_name',
        'owner_id',
        'u.name as owner_name',
        'images_array',
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
      ->get([
        'products.id',
        'products.name',
        'disc',
        'long_disc',
        'products.type_id',
        'pt.name as type_name',
        'owner_id',
        'u.name as owner_name',
        'images_array',
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

  public function showUserAddProductRequests($id)
  {
    $data = DB::table('add_product_requests as ap')->where('user_id', $id)
      ->join('users as u', 'u.id', 'user_id')
      ->leftjoin('users as uu', 'uu.id', 'employee_id')
      ->join('addresses as ad', 'ad.id', 'product_place')
      ->join('cities as c', 'c.id', 'ad.city_id')
      ->join('countries as co', 'co.id', 'c.country_id')
      ->get([
        'ap.id',
        'user_id',
        'u.name as user_name',
        'employee_id',
        'uu.name as employee_name',
        'images_array',
        'product_name',
        'product_disc',
        'product_quantity',
        'product_price',
        'product_place as addresse_id',
        'ad.name as addresse_',
        'ad.city_id',
        'c.name as city',
        'c.country_id',
        'co.name as country',
        'ap.accepted',
        'ap.blocked',
        'ap.created_at',
        'ap.updated_at'
      ]);

    return $data;
  }
}
