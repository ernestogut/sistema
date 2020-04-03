<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCierreCajasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cierre_cajas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_usuario');
            $table->foreign('id_usuario')->references('id')->on('users');
            $table->unsignedBigInteger('id_almacen');
            $table->foreign('id_almacen')->references('id')->on('almacens');
            $table->date('fecha');
            $table->float('saldo_inicial', 11, 2);
            $table->float('ventas_diarias', 11, 2);
            $table->float('ingresos', 11, 2);
            $table->float('egresos', 11, 2);
            $table->float('saldo_final', 11, 2);
            $table->string('estado')->default('abierto');
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
        Schema::dropIfExists('cierre_cajas');
    }
}
