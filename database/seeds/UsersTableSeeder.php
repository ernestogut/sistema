<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'usuario' => 'julio',
            'nombre' => 'Julio',
            'apellido' => 'Baraybar',
            'password' => bcrypt('123456'),
            'idrole' => '1',
            'condicion' => '1'

        ]);
        DB::table('users')->insert([
            'usuario' => 'marcos',
            'nombre' => 'Marcos',
            'apellido' => 'Bullen',
            'password' => bcrypt('1234'),
            'idrole' => '2',
            'condicion' => '1'
        ]);
        DB::table('users')->insert([
        	'id' => '3',
            'usuario' => 'ernestog',
            'nombre' => 'Ernesto',
            'apellido' => 'Gutierrez',
            'password' => bcrypt('cubosderubik14'),
            'idrole' => '1',
            'condicion' => '1'
        ]);
    }
}
