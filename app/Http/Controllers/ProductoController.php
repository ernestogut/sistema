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
            /*$productos = Producto::select('productos.id','productos.codigo','productos.marca','productos.modelo','productos.precio','productos.descripcion','productos.imagen',
            'almacens.descripcion as descripcion_almacen')->join('almacens','productos.almacen_id', '=', 'almacens.id')->get();*/

            $productos = Producto::select('productos.id','productos.codigo','productos.marca','productos.modelo','productos.cantidad', 'productos.precio','productos.descripcion','productos.imagen')->get();
            //$producto = Producto::All();
            return $productos;
            //$producto = Producto::first();
            //$producto = $producto->almacen;
            
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
        $name = null;
        if($request->hasFile('imagen')){
            $file = $request->file('imagen');
            #($producto['imagen']=$request->file('imagen'))--
            $name = time().$file->getClientOriginalName();
            $file->move(public_path().'/images/', $name);
         #  $producto['imagen']=$request->file('imagen')->store('uploads', 'public');
        }
        $producto = new Producto();
        $producto->codigo = $request->codigo;
        $producto->marca = $request->marca;
        $producto->modelo = $request->modelo;
        $producto->cantidad = $request->cantidad;
        $producto->precio = $request->precio;
        $producto->descripcion = $request->descripcion;
        $producto->imagen = $name;
        //$producto->almacen_id = $request->almacen_id;
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
        $producto->cantidad = $request->cantidad;
        $producto->precio = $request->precio;
        $producto->descripcion = $request->descripcion;
        if($request->hasFile('imagen')){
            $file = $request->file('imagen');
            #($producto['imagen']=$request->file('imagen'))--
            $name = time().$file->getClientOriginalName();
            $file->move(public_path().'/images/', $name);
            $producto->imagen = $name;
         #   $producto['imagen']=$request->file('imagen')->store('uploads', 'public');
        }
        //$producto->almacen_id = $request->almacen_id;
        $producto->save();
        return $producto;
    }
    public function modificarInventario(Request $request)
    {
        dd('ctmre');
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
