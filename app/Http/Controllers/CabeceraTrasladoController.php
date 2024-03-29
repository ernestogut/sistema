<?php

namespace App\Http\Controllers;

use App\CabeceraTraslado;
use App\DetalleTraslado;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CabeceraTrasladoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cabecera_traslado = CabeceraTraslado::select('cabecera_traslados.id as num_documento', 'cabecera_traslados.id_almacen_destino as id_alm_destino', 'cabecera_traslados.id_almacen_origen as id_alm_origen', 'a1.descripcion as almacen_origen', 'a2.descripcion as almacen_destino', 'users.usuario as responsable','cabecera_traslados.fecha_emision', 'cabecera_traslados.motivo',  'cabecera_traslados.observacion', 'cabecera_traslados.estado')->join('almacens as a1','cabecera_traslados.id_almacen_origen', '=', 'a1.id')->join('almacens as a2','cabecera_traslados.id_almacen_destino', '=', 'a2.id')->join('users','cabecera_traslados.id_usuario', '=', 'users.id')->orderBy('cabecera_traslados.id', 'desc')->get();
        return $cabecera_traslado;
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

        $cabecera = $request->traslado;
        $detalle = $request->ventas;

        $cabecera_traslado = new CabeceraTraslado();
        $cabecera_traslado->id_almacen_origen = $cabecera['id_almacen_origen'];
        $cabecera_traslado->id_almacen_destino = $cabecera['id_almacen_destino'];
        $cabecera_traslado->id_usuario = $cabecera['id_usuario'];
        $cabecera_traslado->fecha_emision = $cabecera['fecha_emision'];
        $cabecera_traslado->motivo = $cabecera['motivo'];
        $cabecera_traslado->observacion = $cabecera['observacion'];
        $cabecera_traslado->save();
        $traslado = CabeceraTraslado::select('cabecera_traslados.id as num_documento', 'cabecera_traslados.id_almacen_destino as id_alm_destino', 'cabecera_traslados.id_almacen_origen as id_alm_origen', 'a1.descripcion as almacen_origen', 'a2.descripcion as almacen_destino', 'users.nombre as responsable','cabecera_traslados.fecha_emision', 'cabecera_traslados.motivo',  'cabecera_traslados.observacion', 'cabecera_traslados.estado')->join('almacens as a1','cabecera_traslados.id_almacen_origen', '=', 'a1.id')->join('almacens as a2','cabecera_traslados.id_almacen_destino', '=', 'a2.id')->join('users','cabecera_traslados.id_usuario', '=', 'users.id')->orderBy('cabecera_traslados.id', 'desc')->first();

        foreach ($detalle as $key => $value) {
            $detalle_traslado = new DetalleTraslado();
            $detalle_traslado->id_cabecera_traslado = $traslado->num_documento;
            $detalle_traslado->codigo_producto = $value['codigo'];
            $detalle_traslado->descripcion_producto = $value['producto'];
            $detalle_traslado->cantidad_producto = $value['cantidad'];
            $detalle_traslado->save();
        }
        return $traslado;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CabeceraTraslado  $cabeceraTraslado
     * @return \Illuminate\Http\Response
     */
    public function show(CabeceraTraslado $cabeceraTraslado)
    {
        //
    }
    public function obtenerTrasladosPendientes(){
        $traslados = CabeceraTraslado::select(DB::raw("COUNT(*) as cantidad"))->where('estado', 'pendiente')->get();
        return $traslados;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CabeceraTraslado  $cabeceraTraslado
     * @return \Illuminate\Http\Response
     */
    public function edit(CabeceraTraslado $cabeceraTraslado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CabeceraTraslado  $cabeceraTraslado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CabeceraTraslado $cabeceraTraslado)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CabeceraTraslado  $cabeceraTraslado
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cabecera_traslado = CabeceraTraslado::find($id);
        $cabecera_traslado->delete();
    }
}
