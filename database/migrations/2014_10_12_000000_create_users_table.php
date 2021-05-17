<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->int('role_id');
            $table->string('fullname');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('usertype')->default('user');
            $table->boolean('isActivated')->default(0);
            $table->string('passwordRestCode')->nullable();
            $table->string('activationCode')->nullable();
            $table->string('socialType')->nullable();


             

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
        Schema::dropIfExists('users');
    }
}