<?php

namespace Database\Seeders;

use App\Models\address;
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

        $json = file_get_contents(resource_path('jo2.json'));
        $data = json_decode($json, true);

        foreach ($data as $item) {
            if (!isset($item['admin_name'])) {
                continue;
            }

            $existingCity = City::where('name', $item['admin_name'])->first();

            if ($existingCity) {
                $cityId = $existingCity->id;
            } else {
                $city = City::create([
                    'name' => $item['admin_name'],
                    'country_id' => 1,
                ]);
                $cityId = $city->id;
            }
            DB::table('addresses')->insert([
                'name' => $item['city'],
                'delivery_price' => 5,
                'city_id' => $cityId,
            ]);
        }
    }
}
