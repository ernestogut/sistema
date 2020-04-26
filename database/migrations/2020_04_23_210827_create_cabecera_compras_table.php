<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCabeceraComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cabecera_compras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cod_proveedor')->nullable();
            $table->string('ruc_proveedor')->nullable();
            $table->string('dir_proveedor')->nullable();
            $table->string('razon')->nullable();
            $table->unsignedBigInteger('id_user');
            $table->string('fecha');
            $table->decimal('sub_total', 11, 2);
            $table->decimal('desc_global', 11, 2);
            $table->decimal('igv_total', 11, 2);
            $table->decimal('total', 11, 2);
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
        Schema::dropIfExists('cabecera_compras');
    }
}
