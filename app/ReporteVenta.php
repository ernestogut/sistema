<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReporteVenta extends Model
{
    protected $connection = 'mysql';

    //protected $table = 'ventas_speed';
    protected $table = 'c_facts';
    
}
