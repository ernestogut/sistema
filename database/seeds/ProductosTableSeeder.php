<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('productos')->insert([
            'codigo' => 'P001',
            'marca' => 'Shengshou',
            'modelo' => 'Legend',
            'cantidad' => 50,
            'precio' => 22.00,
            'descripcion' => 'Cubo gama baja',
            'imagen' => '0.jpg',
            //'almacen_id' => 1
        ]);
    }
}
