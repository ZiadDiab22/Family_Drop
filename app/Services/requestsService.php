<?php


namespace App\Services;

use App\Models\Add_product_request;
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
    $data = DB::table('add_product_requests as ap')
      ->join('users as u', 'u.id', 'user_id')
      ->leftjoin('users as uu', 'uu.id', 'employee_id')
      ->join('addresses as ad', 'ad.id', 'product_place')
      ->join('cities as c', 'c.id', 'ad.city_id')
      ->join('countries as co', 'co.id', 'c.country_id')
      ->join('countries as uc', 'u.country_id', 'uc.id')
      ->join('user_types as ut', 'ut.id', 'u.type_id')
      ->get([
        'ap.id',
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
        'ap.accepted',
        'ap.blocked',
        'ap.created_at',
        'ap.updated_at'
      ]);

    return $data;
  }

  public function getUserAddProductRequests($id)
  {
    $data = DB::table('add_product_requests as ap')->where('user_id', $id)
      ->join('users as u', 'u.id', 'user_id')
      ->leftjoin('users as uu', 'uu.id', 'employee_id')
      ->join('addresses as ad', 'ad.id', 'product_place')
      ->join('cities as c', 'c.id', 'ad.city_id')
      ->join('countries as co', 'co.id', 'c.country_id')
      ->join('countries as uc', 'u.country_id', 'uc.id')
      ->join('user_types as ut', 'ut.id', 'u.type_id')
      ->get([
        'ap.id',
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
        'ap.accepted',
        'ap.blocked',
        'ap.created_at',
        'ap.updated_at'
      ]);

    return $data;
  }

  public function getPullProductRequests()
  {
    $data = DB::table('pull_product_requests as pp')
      ->join('products as p', 'p.id', 'product_id')
      ->join('product_types as pt', 'p.type_id', 'pt.id')
      ->leftjoin('users as u', 'u.id', 'employee_id')
      ->join('users as uu', 'uu.id', 'mercher_id')
      ->join('countries as c', 'uu.country_id', 'c.id')
      ->join('user_types as ut', 'ut.id', 'uu.type_id')
      ->get([
        'pp.id',
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
        'pp.quantity as request_quantity',
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
        'pp.blocked',
        'pp.created_at',
        'pp.updated_at'
      ]);

    return $data;
  }

  public function getUserPullProductRequests($id)
  {
    $data = DB::table('pull_product_requests as pp')->where('mercher_id', $id)
      ->join('products as p', 'p.id', 'product_id')
      ->join('product_types as pt', 'p.type_id', 'pt.id')
      ->leftjoin('users as u', 'u.id', 'employee_id')
      ->join('users as uu', 'uu.id', 'mercher_id')
      ->join('countries as c', 'uu.country_id', 'c.id')
      ->join('user_types as ut', 'ut.id', 'uu.type_id')
      ->get([
        'pp.id',
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
        'pp.quantity as request_quantity',
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
        'pp.blocked',
        'pp.created_at',
        'pp.updated_at'
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
      ->sum('total');

    return $data;
  }

  public function getPinnedProducts($id)
  {
    $data = DB::table('add_product_requests as ap')->where('user_id', $id)
      ->where('accepted', 0)
      ->join('users as u', 'u.id', 'user_id')
      ->leftjoin('users as uu', 'uu.id', 'employee_id')
      ->join('addresses as ad', 'ad.id', 'product_place')
      ->join('cities as c', 'c.id', 'ad.city_id')
      ->join('countries as co', 'co.id', 'c.country_id')
      ->join('countries as uc', 'u.country_id', 'uc.id')
      ->join('user_types as ut', 'ut.id', 'u.type_id')
      ->get([
        'ap.id',
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
        'ap.accepted',
        'ap.blocked',
        'ap.created_at',
        'ap.updated_at'
      ]);

    return $data;
  }

  public function getPulledProducts($id)
  {
    $data = DB::table('pull_product_requests as pp')->where('mercher_id', $id)
      ->where('accepted', 1)
      ->join('products as p', 'p.id', 'product_id')
      ->join('product_types as pt', 'p.type_id', 'pt.id')
      ->leftjoin('users as u', 'u.id', 'employee_id')
      ->join('users as uu', 'uu.id', 'mercher_id')
      ->join('countries as c', 'uu.country_id', 'c.id')
      ->join('user_types as ut', 'ut.id', 'uu.type_id')
      ->get([
        'pp.id',
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
        'pp.quantity as request_quantity',
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
        'pp.blocked',
        'pp.created_at',
        'pp.updated_at'
      ]);

    return $data;
  }
}
