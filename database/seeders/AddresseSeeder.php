<?php

namespace Database\Seeders;

use App\Models\address;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddresseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('addresses')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        address::create([
            "name" => "Mazzeh",
            "city_id" => 1,
        ]);
        address::create([
            "name" => "Muhajirin",
            "city_id" => 1,
        ]);
        address::create([
            "name" => "Baramkeh",
            "city_id" => 2,
        ]);
        address::create([
            "name" => "Adawi",
            "city_id" => 3,
        ]);
        address::create([
            "name" => "Hadara",
            "city_id" => 2,
        ]);
        address::create([
            "name" => "October",
            "city_id" => 3,
        ]);
        address::create([
            "name" => "Zaid",
            "city_id" => 3,
        ]);
        address::create([
            "name" => "Dawod",
            "city_id" => 4,
        ]);
    }
}
