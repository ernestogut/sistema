<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCFactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_facts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_tipo_comprobante');
            $table->foreign('id_tipo_comprobante')->references('id')->on('tipo_comprobantes');
            $table->unsignedBigInteger('id_almacen');
            $table->foreign('id_almacen')->references('id')->on('almacens');
            $table->string('cod_cliente');
            $table->string('ruc_cliente');
            $table->string('dir_cliente');
            $table->string('razon');
            $table->unsignedBigInteger('id_user');
            $table->string('fecha');
            $table->string('tipo_venta');
            $table->string('tipo_pago');
            $table->string('serie');
            $table->string('folio');
            $table->decimal('sub_total', 11, 2);
            $table->decimal('desc_global', 11, 2);
            $table->decimal('igv_total', 11, 2);
            $table->decimal('total', 11, 2);
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
        Schema::dropIfExists('c_facts');
    }
}
