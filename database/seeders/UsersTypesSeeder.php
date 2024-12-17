<?php

namespace Database\Seeders;

use App\Models\User_type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('user_types')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        User_type::create([
            "name" => "Admin",
        ]);
        User_type::create([
            "name" => "Employee",
        ]);
        User_type::create([
            "name" => "Mercher",
        ]);
        User_type::create([
            "name" => "Marketer",
        ]);
        User_type::create([
            "name" => "Customer",
        ]);
    }
}
