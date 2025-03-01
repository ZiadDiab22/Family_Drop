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
        Schema::create('add_product_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('employee_id')->nullable()->default(null);
            $table->string('product_name');
            $table->json('images_array')->nullable()->default(null);
            $table->string('product_quantity');
            $table->float('product_price');
            $table->string('product_disc');
            $table->string('product_place');
            $table->boolean('accepted')->nullable()->default(false);
            $table->boolean('blocked')->nullable()->default(false);
            $table->timestamps();
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')
                ->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_product_requests');
    }
};
