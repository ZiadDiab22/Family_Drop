<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pull_product_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('mercher_id');
            $table->unsignedInteger('employee_id');
            $table->unsignedInteger('product_id');
            $table->integer('quantity');
            $table->boolean('accepted')->nullable()->default(false);
            $table->boolean('blocked')->nullable()->default(false);
            $table->timestamps();
            $table->foreign('mercher_id')->references('id')
                ->on('users')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')
                ->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')
                ->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pull_product_requests');
    }
};
