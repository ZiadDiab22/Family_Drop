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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('country_id')->nullable()->default(null);
            $table->unsignedInteger('type_id');
            $table->string('email', 50)->unique();
            $table->string('password')->unique();
            $table->string('phone_no')->unique();
            $table->string('code_auth')->nullable()->default(null);
            $table->integer('badget')->nullable()->default(0);
            $table->timestamps();
            $table->foreign('country_id')->references('id')
                ->on('countries')->onDelete('cascade');
            $table->foreign('type_id')->references('id')
                ->on('user_types')->onDelete('cascade');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
