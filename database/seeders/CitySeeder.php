<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('cities')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        City::create([
            "name" => "Damascus",
            "country_id" => 1,
        ]);
        City::create([
            "name" => "Homs",
            "country_id" => 1,
        ]);
        City::create([
            "name" => "Cairo",
            "country_id" => 2,
        ]);
        City::create([
            "name" => "Amman",
            "country_id" => 3,
        ]);
    }
}
