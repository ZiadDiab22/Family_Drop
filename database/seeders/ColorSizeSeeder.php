<?php

namespace Database\Seeders;

use App\Models\color;
use App\Models\size;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ColorSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('colors')->truncate();
        DB::table('sizes')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        size::create([
            "name" => "10",
        ]);
        size::create([
            "name" => "15",
        ]);
        size::create([
            "name" => "8.5",
        ]);
        size::create([
            "name" => "26",
        ]);
        color::create([
            "name" => "Red",
            "code" => "#281822",
        ]);
        color::create([
            "name" => "Blue",
            "code" => "#848733",
        ]);
        color::create([
            "name" => "Black",
            "code" => "#656474",
        ]);
        color::create([
            "name" => "Orange",
            "code" => "#7627894",
        ]);
    }
}
