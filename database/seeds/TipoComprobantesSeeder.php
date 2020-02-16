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
            'nombre' => 'Factura Cliente',
            'descripcion' => 'Factura Cliente',
        ]);
        DB::table('tipo_comprobantes')->insert([
            'nombre' => 'Boleta Cliente',
            'descripcion' => 'Boleta Cliente',
        ]);
        DB::table('tipo_comprobantes')->insert([
            'nombre' => 'Nota de Crédito Cliente',
            'descripcion' => 'Nota de Crédito Cliente',
        ]);
        DB::table('tipo_comprobantes')->insert([
            'nombre' => 'Nota de Débito Cliente',
            'descripcion' => 'Nota de Débito Cliente',
        ]);
    }
}
