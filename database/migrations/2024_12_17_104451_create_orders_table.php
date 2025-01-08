<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('state_id');
            $table->unsignedInteger('addresse_id');
            $table->longText('title')->nullable()->default(null);
            $table->string('customer_name');
            $table->string('customer_number')->nullable()->default(null);
            $table->float('total_price');
            $table->integer('total_quantity');
            $table->boolean('blocked')->nullable()->default(false);
            $table->timestamps();
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');
            $table->foreign('state_id')->references('id')
                ->on('order_states')->onDelete('cascade');
            $table->foreign('addresse_id')->references('id')
                ->on('addresses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
