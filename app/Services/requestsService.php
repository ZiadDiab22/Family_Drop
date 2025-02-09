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
      ->get([
        'pull_requests.id',
        'employee_id',
        'u.name as employee_name',
        'user_id',
        'uu.name as user_name',
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
      ->get([
        'pull_requests.id',
        'employee_id',
        'u.name as employee_name',
        'user_id',
        'uu.name as user_name',
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
    $data = Add_product_request::leftjoin('users as u', 'u.id', 'employee_id')
      ->join('users as uu', 'uu.id', 'user_id')
      ->get([
        'add_product_requests.id',
        'employee_id',
        'u.name as employee_name',
        'user_id',
        'uu.name as user_name',
        'product_name',
        'images_array',
        'product_quantity',
        'product_price',
        'product_disc',
        'product_place',
        'accepted',
        'add_product_requests.blocked',
        'add_product_requests.created_at',
        'add_product_requests.updated_at'
      ]);

    return $data;
  }

  public function getPullProductRequests()
  {
    $data = pull_product_request::leftjoin('users as u', 'u.id', 'employee_id')
      ->join('users as uu', 'uu.id', 'mercher_id')
      ->join('products as p', 'p.id', 'product_id')
      ->get([
        'pull_product_requests.id',
        'employee_id',
        'u.name as employee_name',
        'mercher_id',
        'uu.name as mercher_name',
        'product_id',
        'p.name',
        'p.disc',
        'pull_product_requests.quantity',
        'accepted',
        'pull_product_requests.blocked',
        'pull_product_requests.created_at',
        'pull_product_requests.updated_at'
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
      ->get([
        'pp.id',
        'employee_id',
        'u.name as employee_name',
        'mercher_id',
        'uu.name as mercher_name',
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
      ->get([
        'pull_requests.id',
        'employee_id',
        'u.name as employee_name',
        'user_id',
        'uu.name as user_name',
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
    $data = Add_product_request::where('user_id', $id)->where('accepted', 0)
      ->leftjoin('users as u', 'u.id', 'employee_id')
      ->join('users as uu', 'uu.id', 'user_id')
      ->get([
        'add_product_requests.id',
        'employee_id',
        'u.name as employee_name',
        'user_id',
        'uu.name as user_name',
        'product_name',
        'images_array',
        'product_quantity',
        'product_price',
        'product_disc',
        'product_place',
        'accepted',
        'add_product_requests.blocked',
        'add_product_requests.created_at',
        'add_product_requests.updated_at'
      ]);

    return $data;
  }

  public function getPulledProducts($id)
  {
    $data = DB::table('pull_product_requests as pp')
      ->where('mercher_id', $id)
      ->where('accepted', 1)
      ->join('products as p', 'p.id', 'product_id')
      ->join('product_types as pt', 'p.type_id', 'pt.id')
      ->leftjoin('users as u', 'u.id', 'employee_id')
      ->join('users as uu', 'uu.id', 'mercher_id')
      ->get([
        'pp.id',
        'employee_id',
        'u.name as employee_name',
        'mercher_id',
        'uu.name as mercher_name',
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
