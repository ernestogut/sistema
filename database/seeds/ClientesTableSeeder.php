<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('clientes')->insert([
            'id_tipo_doc' => '1',
            'codigo' => 'C123456789101',
            'razon' => 'razon cliente',
            'direccion' => 'direccion cliente',
            'num_documento' => '123456789101'
        ]);
    }
}
