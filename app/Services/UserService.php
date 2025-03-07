<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

  public function getProfitsDatesValues($date1, $date2)
  {
    $data = [];
    $profit_dates = [];
    $profit_values = [];

    $profits = DB::table('orders')
      ->whereDate('created_at', '>=', $date1)
      ->whereDate('created_at', '<=', $date2)
      ->selectRaw("
                DATE_FORMAT(DATE(created_at), '%Y-%m') as month,
                SUM(total_profit) as total_profit
            ")
      ->groupByRaw('DATE_FORMAT(DATE(created_at), "%Y-%m")')
      ->orderByRaw('month ASC')
      ->get();

    $dates = $profits->pluck('month')->toArray();
    $values = $profits->pluck('total_profit')->toArray();

    $date1 = Carbon::parse($date1);
    $date2 = Carbon::parse($date2);

    $currentDate = clone $date1;
    while ($currentDate <= $date2) {
      $monthKey = $currentDate->format('Y-m');
      $profit_dates[] = $monthKey;

      $index = array_search($monthKey, $dates);
      if ($index !== false) {
        $profit_values[] = $values[$index];
      } else {
        $profit_values[] = 0;
      }

      // Move to next month
      $currentDate->modify('first day of next month');
    }

    $marketer_percent = DB::table('settings')->value('value');

    $profit_values = collect($profit_values)->map(function ($value) use ($marketer_percent) {
      return round($value * ((100 - $marketer_percent) / 100), 2);
    })->all();

    array_push($data, $profit_dates, $profit_values);

    return $data;
  }
}
