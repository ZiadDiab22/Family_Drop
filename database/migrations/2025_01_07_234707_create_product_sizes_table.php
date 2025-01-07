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
        Schema::create('product_sizes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('size_id');
            $table->unsignedInteger('product_id');
            $table->foreign('size_id')->references('id')
                ->on('sizes')->onDelete('cascade');
            $table->foreign('product_id')->references('id')
                ->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_sizes');
    }
};
