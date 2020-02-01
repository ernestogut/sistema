<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    public function almacen(){
        return $this->belongsTo(Almacen::class);
    }
}