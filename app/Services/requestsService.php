<?php


namespace App\Services;

use App\Models\Add_product_request;
use App\Models\pull_product_request;
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
}
