<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoucherPedidoPorDiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voucher_pedido_por_dias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('fecha_dia');
            $table->string('voucher')->default('default.jpg');
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
        Schema::dropIfExists('voucher_pedido_por_dias');
    }
}
