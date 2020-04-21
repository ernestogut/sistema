<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSerieComprobantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('serie_comprobantes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_tipo_comprobante');
            $table->foreign('id_tipo_comprobante')->references('id')->on('tipo_comprobantes');
            $table->unsignedBigInteger('id_almacen');
            $table->foreign('id_almacen')->references('id')->on('almacens');
            $table->string('serie');
            $table->bigInteger('numero_inicial');
            $table->bigInteger('numero_actual');
            $table->bigInteger('numero_final');
            $table->string('anio');
            $table->string('estado')->default('habilitado');
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
        Schema::dropIfExists('serie_comprobantes');
    }
}
