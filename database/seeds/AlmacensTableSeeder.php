<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlmacensTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('almacens')->insert([
            'codigo' => 'ALM001',
            'descripcion' => 'Lince',
            'direccion' => 'Av. Arenales 1624'
        ]);
    }
}
