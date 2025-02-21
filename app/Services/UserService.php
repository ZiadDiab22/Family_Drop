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
}
