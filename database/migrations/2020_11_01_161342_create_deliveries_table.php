<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_usuario');
            $table->foreign('id_usuario')->references('id')->on('users');
            $table->date('fecha');
            $table->time('hora');
            $table->text('cliente');
            $table->string('telefono');
            $table->text('direccion');
            $table->text('referencia')->nullable();
            $table->unsignedBigInteger('distrito');
            $table->foreign('distrito')->references('id')->on('precios_deliveries');
            $table->text('pedido');
            $table->text('observacion_empresa')->nullable();
            $table->text('observacion_delivery')->nullable();
            $table->text('medio_recepcion');
            $table->string('metodo_pago');
            $table->decimal('precio_productos', 11, 2);
            $table->decimal('envio_productos', 11, 2);
            $table->decimal('total_pedido', 11, 2);
            $table->decimal('monto_pagado', 11, 2);
            $table->decimal('por_cobrar', 11, 2);
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
        Schema::dropIfExists('deliveries');
    }
}
