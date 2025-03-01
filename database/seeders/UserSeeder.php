<?php

namespace Database\Seeders;

use App\Models\Payment_way;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::table('payment_ways')->truncate();
        DB::table('settings')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        User::create([
            "name" => "Ahmad",
            "type_id" => 1,
            "email" => "aa@gmail.com",
            "password" => bcrypt("111"),
            "phone_no" => "0999",
            "country_id" => 1,
        ]);

        Payment_way::create([
            "name" => "test",
            "data" => "test",
        ]);

        Setting::create([
            'name' => 'Marketer Commission',
            'value' => 50
        ]);
    }
}
