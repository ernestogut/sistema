<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {

            /*$procedure = "
                CREATE PROCEDURE `disminuirInventario`(codigo_prod VARCHAR, cantidad_prod BIGINT)
                BEGIN

                    DECLARE cantidad_producto BIGINT;

                    SET cantidad_producto = (SELECT (cantidad - cantidad_prod) FROM productos WHERE codigo = CONVERT(codigo_prod using utf8mb4) COLLATE utf8mb4_general_ci); 




                    UPDATE `productos` SET cantidad = cantidad_producto where codigo = CONVERT(codigo_prod using utf8mb4) collate utf8mb4_general_ci;

                END
            ";*/

            /*DB::unprepared("DROP procedure IF EXISTS disminuirInventario");
            DB::unprepared($procedure);*/
            $table->bigIncrements('id');
            $table->string('codigo', 20)->unique();
            $table->string('marca');
            $table->string('modelo');
            $table->bigInteger('cantidad');
            $table->float('precio');
            $table->longText('descripcion');
            $table->string('imagen')->nullable();
            /*$table->unsignedBigInteger('almacen_id');
            $table->foreign('almacen_id')->references('id')->on('almacens');*/
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
        Schema::dropIfExists('productos');
    }
}
