<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleSalidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_salidas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_cabecera_salida');
            $table->foreign('id_cabecera_salida')->references('id')->on('cabecera_salidas')->onDelete('cascade');
            $table->string('codigo_producto');
            $table->string('descripcion_producto');
            $table->text('cantidades');
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
        Schema::dropIfExists('detalle_salidas');
    }
}
