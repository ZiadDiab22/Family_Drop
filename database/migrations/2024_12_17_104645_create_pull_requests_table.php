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
        Schema::create('pull_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('payment_way_id');
            $table->unsignedInteger('employee_id');
            $table->unsignedInteger('user_id');
            $table->string('total');
            $table->boolean('accepted')->nullable()->default(false);
            $table->boolean('blocked')->nullable()->default(false);
            $table->timestamps();
            $table->foreign('payment_way_id')->references('id')
                ->on('payment_ways')->onDelete('cascade');
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
        Schema::dropIfExists('pull_requests');
    }
};
