<?php

namespace App\Http\Controllers;

use App\Almacen;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AlmacenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$request->ajax()){
            return redirect('/');
        }else{
            $almacen = Almacen::All();
            return $almacen;
        }   
    }
    public function cantidadesAlmacen(Request $request, $codigo)
    {
        if(!$request->ajax()){
           return redirect('/');
        }else{
            $almacen = Almacen::select('almacens.id', 'almacens.codigo', 'almacens.descripcion','almacens.direccion', 'inventarios.cantidad as cantidad', 'inventarios.id as id_inventario')->leftJoin('inventarios','almacens.id', '=', 'inventarios.id_almacen')->where('inventarios.id_producto', '=', $codigo)->get();

            foreach ($almacen as $valor){
                $valor['editable'] = true;
            }
            return $almacen;
        }   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $almacen = new Almacen();
        #if($request->hasFile('imagen')){
         #   $almacen['imagen']=$request->file('imagen')->store('uploads', 'public');
        #}
        $almacen->codigo = $request->codigo;
        $almacen->descripcion = $request->descripcion;
        $almacen->direccion = $request->direccion;
        #$almacen->imagen = $request->imagen;
        $almacen->save();
        #$almacen = request()->all();
        #$almacen = request()->except('_token');
        #almacen::insert($almacen);
        #return response()->json($almacen);
        return $almacen;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Almacen  $almacen
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $almacen = Almacen::find($id);
        return $almacen;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Almacen  $almacen
     * @return \Illuminate\Http\Response
     */
    public function edit(Almacen $almacen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Almacen  $almacen
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $almacen = Almacen::find($id);
        $almacen->codigo = $request->codigo;
        $almacen->descripcion = $request->descripcion;
        $almacen->direccion = $request->direccion;
        $almacen->save();
        return $almacen;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Almacen  $almacen
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $almacen = Almacen::find($id);
        $almacen->delete();
    }
}
