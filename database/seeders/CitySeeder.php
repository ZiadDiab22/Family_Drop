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
            "name" => "دمشق",
            "country_id" => 1,
        ]);
        City::create([
            "name" => "حمص",
            "country_id" => 1,
        ]);
        City::create([
            "name" => "القاهرة",
            "country_id" => 2,
        ]);

        $json = file_get_contents(resource_path('jo2.json'));
        $data = json_decode($json, true);
        foreach ($data as $item) {
            DB::table('cities')->insert([
                'name' => $item['name'],
                'country_id' => 3,
            ]);
        }
    }
}
