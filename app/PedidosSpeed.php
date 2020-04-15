<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PedidosSpeed extends Model
{
    protected $connection = 'speed';

	protected $table = 'ordenes_tienda';
	//protected $casts = [
    //    'stock' => 'integer',
    //];
}
