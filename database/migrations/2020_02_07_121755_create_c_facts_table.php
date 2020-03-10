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
            $table->string('cod_cliente');
            $table->string('ruc_cliente');
            $table->string('dir_cliente');
            $table->string('razon');
            $table->unsignedBigInteger('id_user');
            $table->string('fecha');
            $table->string('tipo_venta');
            $table->string('serie');
            $table->string('folio');
            $table->float('sub_total');
            $table->float('desc_global');
            $table->float('igv_total');
            $table->float('total');
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
