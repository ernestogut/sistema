<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoDocumentosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_documentos')->insert([
            'tipo_doc' => 'RUC',
            'descripcion' => 'RUC para facturar'
        ]);
        DB::table('tipo_documentos')->insert([
            'tipo_doc' => 'DNI',
            'descripcion' => 'Documento de identidad nacional del Perú'
        ]);
    }
}
