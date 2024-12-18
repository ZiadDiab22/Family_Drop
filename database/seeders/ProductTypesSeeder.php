<?php

namespace Database\Seeders;

use App\Models\Product_type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('product_types')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Product_type::create([
            "name" => "Personal Computers",
            "classify_id" => 1,
        ]);
        Product_type::create([
            "name" => "Mobile Phones",
            "classify_id" => 1,
        ]);
        Product_type::create([
            "name" => "Tables",
            "classify_id" => 2,
        ]);
        Product_type::create([
            "name" => "Fruit",
            "classify_id" => 3,
        ]);
        Product_type::create([
            "name" => "Sciense Books",
            "classify_id" => 4,
        ]);
    }
}
