<?php

namespace Database\Seeders;

use App\Models\Product_classify;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProductClassifiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('product_classifies')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Product_classify::create([
            "name" => "Electronics",
        ]);
        Product_classify::create([
            "name" => "Furniture",
        ]);
        Product_classify::create([
            "name" => "Food",
        ]);
        Product_classify::create([
            "name" => "Books",
        ]);
    }
}
