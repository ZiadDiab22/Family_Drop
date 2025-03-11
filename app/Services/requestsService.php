<?php


namespace App\Services;

use App\Models\Add_product_request;
use App\Models\pull_product_request;
use Illuminate\Support\Facades\DB;
use App\Models\Pull_request;

class requestsService
{
  public function getPullRequests()
  {
    $data = Pull_request::join('payment_ways as p', 'p.id', 'payment_way_id')
      ->leftjoin('users as u', 'u.id', 'employee_id')
      ->join('users as uu', 'uu.id', 'user_id')
      ->join('countries as c', 'uu.country_id', 'c.id')
      ->join('user_types as ut', 'ut.id', 'uu.type_id')
      ->get([
        'pull_requests.id',
        'employee_id',
        'u.name as employee_name',
        'user_id',
        'uu.name as user_name',
        'uu.email',
        'uu.phone_no',
        'uu.country_id as user_country_id',
        'c.name as user_country',
        'uu.type_id as user_type_id',
        'ut.name as user_type',
        'total',
        'payment_data',
        'payment_way_id',
        'p.name',
        'accepted',
        'pull_requests.blocked',
        'pull_requests.created_at',
        'pull_requests.updated_at'
      ]);

    return $data;
  }

  public function getUserPullRequests($id)
  {
    $data = Pull_request::where('user_id', $id)->join('payment_ways as p', 'p.id', 'payment_way_id')
      ->leftjoin('users as u', 'u.id', 'employee_id')
      ->join('users as uu', 'uu.id', 'user_id')
      ->join('countries as c', 'uu.country_id', 'c.id')
      ->join('user_types as ut', 'ut.id', 'uu.type_id')
      ->get([
        'pull_requests.id',
        'employee_id',
        'u.name as employee_name',
        'user_id',
        'uu.name as user_name',
        'uu.email',
        'uu.phone_no',
        'uu.country_id as user_country_id',
        'c.name as user_country',
        'uu.type_id as user_type_id',
        'ut.name as user_type',
        'total',
        'payment_data',
        'payment_way_id',
        'p.name',
        'accepted',
        'pull_requests.blocked',
        'pull_requests.created_at',
        'pull_requests.updated_at'
      ]);

    return $data;
  }

  public function getAddProductRequests()
  {
    $data = Add_product_request::join('users as u', 'u.id', 'user_id')
      ->leftjoin('users as uu', 'uu.id', 'employee_id')
      ->join('addresses as ad', 'ad.id', 'product_place')
      ->join('cities as c', 'c.id', 'ad.city_id')
      ->join('countries as co', 'co.id', 'c.country_id')
      ->join('countries as uc', 'u.country_id', 'uc.id')
      ->join('user_types as ut', 'ut.id', 'u.type_id')
      ->get([
        'add_product_requests.id',
        'user_id',
        'u.name as user_name',
        'u.email',
        'u.phone_no',
        'u.country_id as user_country_id',
        'uc.name as user_country',
        'u.type_id as user_type_id',
        'ut.name as user_type',
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
        'add_product_requests.accepted',
        'add_product_requests.blocked',
        'add_product_requests.created_at',
        'add_product_requests.updated_at'
      ]);

    return $data;
  }

  public function getUserAddProductRequests($id)
  {
    $data = Add_product_request::join('users as u', 'u.id', 'user_id')->where('user_id', $id)
      ->leftjoin('users as uu', 'uu.id', 'employee_id')
      ->join('addresses as ad', 'ad.id', 'product_place')
      ->join('cities as c', 'c.id', 'ad.city_id')
      ->join('countries as co', 'co.id', 'c.country_id')
      ->join('countries as uc', 'u.country_id', 'uc.id')
      ->join('user_types as ut', 'ut.id', 'u.type_id')
      ->get([
        'add_product_requests.id',
        'user_id',
        'u.name as user_name',
        'u.email',
        'u.phone_no',
        'u.country_id as user_country_id',
        'uc.name as user_country',
        'u.type_id as user_type_id',
        'ut.name as user_type',
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
        'add_product_requests.accepted',
        'add_product_requests.blocked',
        'add_product_requests.created_at',
        'add_product_requests.updated_at'
      ]);

    return $data;
  }

  public function getPullProductRequests()
  {
    $data = pull_product_request::join('products as p', 'p.id', 'product_id')
      ->join('product_types as pt', 'p.type_id', 'pt.id')
      ->leftjoin('users as u', 'u.id', 'employee_id')
      ->join('users as uu', 'uu.id', 'mercher_id')
      ->join('countries as c', 'uu.country_id', 'c.id')
      ->join('user_types as ut', 'ut.id', 'uu.type_id')
      ->get([
        'pull_product_requests.id',
        'employee_id',
        'u.name as employee_name',
        'mercher_id',
        'uu.name as mercher_name',
        'uu.email',
        'uu.phone_no',
        'uu.country_id as mercher_country_id',
        'c.name as mercher_country',
        'uu.type_id as mercher_type_id',
        'ut.name as mercher_type',
        'pull_product_requests.quantity as request_quantity',
        'product_id',
        'p.name',
        'p.disc',
        'p.long_disc',
        'p.type_id',
        'pt.name as type',
        'p.images_array',
        'p.cost_price',
        'p.quantity',
        'p.sales',
        'p.profit_rate',
        'p.created_at as product_created_at',
        'p.updated_at as product_updated_at',
        'accepted',
        'pull_product_requests.blocked',
        'pull_product_requests.created_at',
        'pull_product_requests.updated_at'
      ]);

    return $data;
  }

  public function getUserPullProductRequests($id)
  {
    $data = pull_product_request::join('products as p', 'p.id', 'product_id')->where('mercher_id', $id)
      ->join('product_types as pt', 'p.type_id', 'pt.id')
      ->leftjoin('users as u', 'u.id', 'employee_id')
      ->join('users as uu', 'uu.id', 'mercher_id')
      ->join('countries as c', 'uu.country_id', 'c.id')
      ->join('user_types as ut', 'ut.id', 'uu.type_id')
      ->get([
        'pull_product_requests.id',
        'employee_id',
        'u.name as employee_name',
        'mercher_id',
        'uu.name as mercher_name',
        'uu.email',
        'uu.phone_no',
        'uu.country_id as mercher_country_id',
        'c.name as mercher_country',
        'uu.type_id as mercher_type_id',
        'ut.name as mercher_type',
        'pull_product_requests.quantity as request_quantity',
        'product_id',
        'p.name',
        'p.disc',
        'p.long_disc',
        'p.type_id',
        'pt.name as type',
        'p.images_array',
        'p.cost_price',
        'p.quantity',
        'p.sales',
        'p.profit_rate',
        'p.created_at as product_created_at',
        'p.updated_at as product_updated_at',
        'accepted',
        'pull_product_requests.blocked',
        'pull_product_requests.created_at',
        'pull_product_requests.updated_at'
      ]);
    return $data;
  }

  public function getUnfinishedUserPullRequests($id)
  {
    $data = Pull_request::where('user_id', $id)
      ->where('accepted', 0)
      ->join('payment_ways as p', 'p.id', 'payment_way_id')
      ->leftjoin('users as u', 'u.id', 'employee_id')
      ->join('users as uu', 'uu.id', 'user_id')
      ->join('countries as c', 'uu.country_id', 'c.id')
      ->join('user_types as ut', 'ut.id', 'uu.type_id')
      ->get([
        'pull_requests.id',
        'employee_id',
        'u.name as employee_name',
        'user_id',
        'uu.name as user_name',
        'uu.email',
        'uu.phone_no',
        'uu.country_id as user_country_id',
        'c.name as user_country',
        'uu.type_id as user_type_id',
        'ut.name as user_type',
        'total',
        'payment_data',
        'payment_way_id',
        'p.name',
        'accepted',
        'pull_requests.blocked',
        'pull_requests.created_at',
        'pull_requests.updated_at'
      ]);

    return $data;
  }

  public function getTotalUnfinishedUserPullRequests($id)
  {
    $data = DB::table('pull_requests')
      ->where('user_id', $id)
      ->where('accepted', 0)
      ->whereNull('employee_id')
      ->sum('total');

    return $data;
  }

  public function getPinnedProducts($id)
  {
    $data = Add_product_request::join('users as u', 'u.id', 'user_id')->where('user_id', $id)
      ->where('accepted', 0)
      ->leftjoin('users as uu', 'uu.id', 'employee_id')
      ->join('addresses as ad', 'ad.id', 'product_place')
      ->join('cities as c', 'c.id', 'ad.city_id')
      ->join('countries as co', 'co.id', 'c.country_id')
      ->join('countries as uc', 'u.country_id', 'uc.id')
      ->join('user_types as ut', 'ut.id', 'u.type_id')
      ->get([
        'add_product_requests.id',
        'user_id',
        'u.name as user_name',
        'u.email',
        'u.phone_no',
        'u.country_id as user_country_id',
        'uc.name as user_country',
        'u.type_id as user_type_id',
        'ut.name as user_type',
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
        'add_product_requests.accepted',
        'add_product_requests.blocked',
        'add_product_requests.created_at',
        'add_product_requests.updated_at'
      ]);

    return $data;
  }

  public function getPulledProducts($id)
  {
    $data = pull_product_request::join('products as p', 'p.id', 'product_id')
      ->where('mercher_id', $id)
      ->where('accepted', 1)
      ->join('product_types as pt', 'p.type_id', 'pt.id')
      ->leftjoin('users as u', 'u.id', 'employee_id')
      ->join('users as uu', 'uu.id', 'mercher_id')
      ->join('countries as c', 'uu.country_id', 'c.id')
      ->join('user_types as ut', 'ut.id', 'uu.type_id')
      ->get([
        'pull_product_requests.id',
        'employee_id',
        'u.name as employee_name',
        'mercher_id',
        'uu.name as mercher_name',
        'uu.email',
        'uu.phone_no',
        'uu.country_id as mercher_country_id',
        'c.name as mercher_country',
        'uu.type_id as mercher_type_id',
        'ut.name as mercher_type',
        'pull_product_requests.quantity as request_quantity',
        'product_id',
        'p.name',
        'p.disc',
        'p.long_disc',
        'p.type_id',
        'pt.name as type',
        'p.images_array',
        'p.cost_price',
        'p.quantity',
        'p.sales',
        'p.profit_rate',
        'p.created_at as product_created_at',
        'p.updated_at as product_updated_at',
        'accepted',
        'pull_product_requests.blocked',
        'pull_product_requests.created_at',
        'pull_product_requests.updated_at'
      ]);

    return $data;
  }

  public function getProductsStats($id)
  {
    $data = [];

    $products = DB::table('products')
      ->where('owner_id', $id)
      ->selectRaw('count(*) as total_products, sum(quantity) as total_quantity, sum(sales) as total_sales')
      ->first();

    array_push($data, $products->total_products, $products->total_quantity, $products->total_sales);

    $reqs = DB::table('add_product_requests')
      ->where('user_id', $id)
      ->where('accepted', 0)
      ->whereNull('employee_id')
      ->select([
        DB::raw('COUNT(*) as count'),
        DB::raw('SUM(product_quantity) as total_quantity')
      ])
      ->first();

    array_push($data, $reqs->count, $reqs->total_quantity);

    $result = DB::table('pull_product_requests')
      ->where('mercher_id', $id)->select([
        DB::raw('COUNT(CASE WHEN employee_id IS NULL THEN 1 END) as pinned'),
        DB::raw('COUNT(CASE WHEN accepted = 1 THEN 1 END) as accepted')
      ])->first();

    $data[] = $result->pinned + $result->accepted;

    $result = DB::table('pull_product_requests')
      ->where('mercher_id', $id)->select([
        DB::raw('SUM(CASE WHEN employee_id IS NULL THEN quantity ELSE 0 END) as pinned'),
        DB::raw('SUM(CASE WHEN accepted = 1 THEN quantity ELSE 0 END) as accepted')
      ])->first();

    $data[] = $result->pinned + $result->accepted;

    $data[] = $data[1] + $data[2] + $data[4] + $data[6];

    return $data;
  }
}
