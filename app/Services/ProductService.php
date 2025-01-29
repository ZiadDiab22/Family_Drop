<?php


namespace App\Services;

use App\Models\Product;
use App\Models\product_color;
use App\Models\product_size;
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
        ->get(['size_id', 's.name as size_name']);

      $colors = product_color::where('product_id', $p['id'])
        ->join('colors as c', 'color_id', 'c.id')
        ->get(['color_id', 'c.name as color_name', 'code']);

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
}
