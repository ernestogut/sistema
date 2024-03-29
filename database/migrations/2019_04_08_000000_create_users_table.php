<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->bigIncrements('id');
            $table->integer('id_almacen')->default(0);
            //$table->foreign('id_almacen')->references('id_almacen')->on('personas')
            $table->string('usuario')->unique();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('password');
            $table->boolean('condicion')->default(1);
            $table->integer('idrole')->unsigned();
            //$table->foreign('idrole')->references('id')->on('roles');

            $table->rememberToken();
            // $table->timestamps();
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
