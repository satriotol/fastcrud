<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fastcrud_error_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->longtext('message');
            $table->longText('trace')->nullable();
            $table->string('error_code')->nullable();
            $table->string('url')->nullable();
            $table->string('method')->nullable();
            $table->longText('input')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->longText('user_agent')->nullable();
            $table->string('environment')->nullable();
            $table->string('level')->default('error');
            $table->boolean('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fastcrud_error_logs');
    }
};
