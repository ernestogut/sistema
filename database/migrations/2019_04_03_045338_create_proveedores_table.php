<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codigo');
            $table->string('nombre', 30)->nullable();
            $table->unsignedBigInteger('id_tipo_doc');
            $table->foreign('id_tipo_doc')->references('id')->on('tipo_documentos');
            $table->string('num_documento')->nullable();
            $table->string('correo', 50)->nullable();
            $table->string('telefono_contacto', 50)->nullable();
            $table->timestamps();
            //$table->foreign('id')->references('id')->on('personas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proveedores');
    }
}
