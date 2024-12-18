<?php

namespace Database\Seeders;

use App\Models\Order_state;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('order_states')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Order_state::create([
            "name" => "new",
        ]);
        Order_state::create([
            "name" => "working",
        ]);
        Order_state::create([
            "name" => "ended",
        ]);
        Order_state::create([
            "name" => "under delivery",
        ]);
        Order_state::create([
            "name" => "cancelled",
        ]);
        Order_state::create([
            "name" => "done",
        ]);
    }
}
