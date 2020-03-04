<?php

namespace App\Http\Controllers;

use App\DetalleIngreso;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetalleIngresoController extends Controller
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

        //dd($request->id_cabecera);
        $detalle = $request->ventas;
        
        foreach ($detalle as $key => $value) {
            //# code...
            //var_dump($key);
            //var_dump($value);
            DB::connection("speed")->statement("call aumentarInventario(?,?)",[$value['codigo'],$value['cantidad']]);   
            /*DB::select("call disminuirInventario(?,?)",[$value['codigo'],$value['cantidad']]);*/
            $detalle_ingreso = new DetalleIngreso();
            $detalle_ingreso->id_cabecera_ingreso = $request->id_cabecera_ingreso;
            $detalle_ingreso->codigo_producto = $value['codigo'];
            $detalle_ingreso->descripcion_producto = $value['producto'];
            //$detalle_ingreso->precio_producto = $value['precio'];
            $detalle_ingreso->cantidad_producto = $value['cantidad'];
            //$detalle_ingreso->descuento_producto = $value['descuento'];
            //$detalle_ingreso->total_producto = $value['total'];
            $detalle_ingreso->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DetalleIngreso  $detalleIngreso
     * @return \Illuminate\Http\Response
     */
    public function show(DetalleIngreso $detalleIngreso)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DetalleIngreso  $detalleIngreso
     * @return \Illuminate\Http\Response
     */
    public function edit(DetalleIngreso $detalleIngreso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DetalleIngreso  $detalleIngreso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DetalleIngreso $detalleIngreso)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DetalleIngreso  $detalleIngreso
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetalleIngreso $detalleIngreso)
    {
        //
    }
}
