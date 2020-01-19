<?php

use Illuminate\Database\Seeder;

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
        	'id' => '1',
            'usuario' => 'julio',
            'password' => bcrypt('123456'),
            'idrole' => '1',
            'condicion' => '0'

        ]);
    }
}
