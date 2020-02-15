<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoComprobantesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_comprobantes')->insert([
            'nombre' => 'Factura',
            'descripcion' => 'Facturación con sunat',
        ]);
    }
}
