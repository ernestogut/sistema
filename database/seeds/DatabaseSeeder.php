<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(AlmacensTableSeeder::class);
        $this->call(ProductosTableSeeder::class);
        $this->call(TipoDocumentosTableSeeder::class);
        $this->call(ClientesTableSeeder::class);
        $this->call(TipoComprobantesSeeder::class);
        $this->call(PreciosDeliverySeeder::class);
        
    }
}
