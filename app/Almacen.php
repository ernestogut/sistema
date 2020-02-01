<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    protected $fillable = ['codigo', 'descripcion', 'direccion'];

    public function productos(){
        return  $this->hasMany(Producto::class);
    }
}
