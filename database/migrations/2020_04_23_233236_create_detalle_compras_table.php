<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_compras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_compra');
            $table->foreign('id_compra')->references('id')->on('cabecera_compras')->onDelete('cascade');
            $table->string('codigo_producto');
            $table->string('descripcion_producto');
            $table->decimal('precio_producto', 11, 2);
            //$table->integer('cantidad_producto');
            $table->text('cantidades');
            $table->decimal('descuento_producto', 11, 2);
            //$table->string('almacen_producto')->nullable();
            $table->decimal('total_producto', 11, 2);
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
        Schema::dropIfExists('detalle_compras');
    }
}
