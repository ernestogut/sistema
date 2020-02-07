<?php

namespace App\Http\Controllers;

use App\D_fact;
use Illuminate\Http\Request;

class DFactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        if(!$request->ajax()) return redirect('/');


        dd($request);
        /*foreach ($request->respuesta as $venta) {
            dd($venta->cantidad);
        }
        $detalle = new D_fact();
        $detalle->id_fact = $request->id_fact;
        $detalle->codigo_producto = $request->codigo_producto;
        $detalle->descripcion_producto = $request->descripcion_producto;
        $detalle->precio_producto = $request->precio_producto;
        $detalle->cantidad_producto = $request->cantidad_producto;
        $detalle->descuento_producto = $request->descuento_producto;
        $detalle->almacen_producto = $request->almacen_producto;
        $detalle->total_producto = $request->total_producto;
        $detalle->save();
        return $detalle;*/
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\D_fact  $d_fact
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if(!$request->ajax()) return redirect('/');
        $detalle = D_fact::find($id);
        return $detalle;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\D_fact  $d_fact
     * @return \Illuminate\Http\Response
     */
    public function edit(D_fact $d_fact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\D_fact  $d_fact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!$request->ajax()) return redirect('/');
        $detalle = D_fact::find($id);
        $detalle->id_fact = $request->id_fact;
        $detalle->codigo_producto = $request->codigo_producto;
        $detalle->descripcion_producto = $request->descripcion_producto;
        $detalle->precio_producto = $request->precio_producto;
        $detalle->cantidad_producto = $request->cantidad_producto;
        $detalle->descuento_producto = $request->descuento_producto;
        $detalle->almacen_producto = $request->almacen_producto;
        $detalle->total_producto = $request->total_producto;
        $detalle->save();
        return $detalle;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\D_fact  $d_fact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $detalle = D_fact::find($id);
        $detalle->delete();
    }
}
