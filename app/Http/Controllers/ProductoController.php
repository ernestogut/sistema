<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductoController extends Controller
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
            $producto = Producto::All();
            return $producto;
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
        $producto = new Producto();
        #if($request->hasFile('imagen')){
         #   $producto['imagen']=$request->file('imagen')->store('uploads', 'public');
        #}
        $producto->codigo = $request->codigo;
        $producto->marca = $request->marca;
        $producto->modelo = $request->modelo;
        $producto->precio = $request->precio;
        $producto->descripcion = $request->descripcion;
        $producto->save();
        return $producto;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $producto = Producto::find($id);
        return $producto;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(Producto $producto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $producto = Producto::find($id);
        $producto->codigo = $request->codigo;
        $producto->marca = $request->marca;
        $producto->modelo = $request->modelo;
        $producto->precio = $request->precio;
        $producto->descripcion = $request->descripcion;
        $producto->save();
        return $producto;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $producto = Producto::find($id);
        $producto->delete();
    }
}
