<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class UserService
{
  public function getUserInfo($id)
  {
    $data = DB::table('users as u')->where('u.id', $id)
      ->join('countries as c', 'c.id', 'country_id')
      ->join('user_types as t', 't.id', 'type_id')
      ->get([
        'u.id',
        'u.name',
        'country_id',
        'c.name as country',
        'type_id',
        't.name as type',
        'email',
        'phone_no',
        'img_url',
        'code_auth',
        'badget',
        'blocked',
        'created_at',
        'updated_at'
      ]);

    return $data;
  }

  public function getUserStats($date1, $date2)
  {
    $data = [];

    for ($i = 3; $i < 5; $i++) {

      $data[] = DB::table('users')
        ->where('type_id', $i)
        ->whereDate('created_at', '>=', $date1)
        ->whereDate('created_at', '<=', $date2)
        ->count();
    }

    return $data;
  }

  public function getTopMarketers($date1, $date2)
  {
    $data = DB::table('orders')
      ->whereDate('orders.created_at', '>=', $date1)
      ->whereDate('orders.created_at', '<=', $date2)
      ->select('users.id', DB::raw('COUNT(*) as value'), 'users.name as label')
      ->join('users', 'orders.user_id', 'users.id')
      ->groupBy('users.id', 'users.name', 'users.id')
      ->orderBy('value', 'desc')
      ->take(6)
      ->get();

    return $data;
  }

  public function getTopMerchers($date1, $date2)
  {
    $data = DB::table('products as p')
      ->whereDate('p.created_at', '>=', $date1)
      ->whereDate('p.created_at', '<=', $date2)
      ->select('users.id', DB::raw('COUNT(*) as value'), 'users.name as label')
      ->join('users', 'p.owner_id', 'users.id')
      ->groupBy('users.id', 'users.name', 'users.id')
      ->orderBy('value', 'desc')
      ->take(6)
      ->get();

    return $data;
  }
}
