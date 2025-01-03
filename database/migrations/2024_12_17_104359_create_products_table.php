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
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('disc');
            $table->text('long_disc');
            $table->unsignedInteger('type_id');
            $table->unsignedInteger('owner_id')->nullable()->default(null);
            $table->string('img_url')->nullable()->default(null);
            $table->float('cost_price');
            $table->float('selling_price');
            $table->integer('quantity');
            $table->float('profit_rate');
            $table->timestamps();
            $table->foreign('owner_id')->references('id')
                ->on('users')->onDelete('cascade');
            $table->foreign('type_id')->references('id')
                ->on('product_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
