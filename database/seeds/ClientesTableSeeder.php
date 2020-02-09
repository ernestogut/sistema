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
            'codigo' => 'C123456789101',
            'razon' => 'razon cliente',
            'direccion' => 'direccion cliente',
            'ruc' => '123456789101'
        ]);
    }
}
