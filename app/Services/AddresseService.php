<?php


namespace App\Services;

use App\Models\address;

class AddresseService
{
  public function showAddresses()
  {
    $data = address::join('cities as ci', 'ci.id', 'addresses.city_id')
      ->join('countries as co', 'co.id', 'country_id')
      ->get([
        'addresses.id',
        'addresses.name as addresse_name',
        'delivery_price',
        'city_id',
        'ci.name as city_name',
        'country_id',
        'co.name as country_name'
      ]);

    return $data;
  }
}
