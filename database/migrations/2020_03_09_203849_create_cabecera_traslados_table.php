<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCabeceraTrasladosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cabecera_traslados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_almacen_origen');
            $table->foreign('id_almacen_origen')->references('id')->on('almacens');
            $table->unsignedBigInteger('id_almacen_destino');
            $table->foreign('id_almacen_destino')->references('id')->on('almacens');
            $table->unsignedBigInteger('id_usuario');
            $table->foreign('id_usuario')->references('id')->on('users');
            $table->date('fecha_emision');
            $table->string('motivo');
            $table->longText('observacion');
            $table->string('estado')->default('pendiente');
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
        Schema::dropIfExists('cabecera_traslados');
    }
}
