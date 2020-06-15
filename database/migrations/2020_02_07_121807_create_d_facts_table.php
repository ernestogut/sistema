<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDFactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('d_facts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_fact');
            $table->foreign('id_fact')->references('id')->on('c_facts')->onDelete('cascade');
            $table->string('codigo_producto');
            $table->string('descripcion_producto');
            $table->decimal('precio_producto', 11, 2);
            $table->integer('cantidad_producto');
            $table->decimal('descuento_producto', 11, 2);
            $table->string('almacen_producto')->nullable();
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
        Schema::dropIfExists('d_facts');
    }
}
