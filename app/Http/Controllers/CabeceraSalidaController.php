<?php

namespace App\Http\Controllers;

use App\CabeceraSalida;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CabeceraSalidaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cabecera_salida = CabeceraSalida::select('cabecera_salidas.id', 'users.usuario as responsable','cabecera_salidas.fecha_emision', 'cabecera_salidas.motivo', 'cabecera_salidas.observacion')->join('users','cabecera_salidas.id_usuario', '=', 'users.id')->orderBy('cabecera_salidas.id', 'desc')->get();


        //$cabecera_salida = CabeceraIngreso::select('cabecera_salidas.id as num_documento', 'almacens.')
        return $cabecera_salida;
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
        $cabecera_salida = new CabeceraSalida();
        $cabecera_salida->id_usuario = $request->id_usuario;
        $cabecera_salida->fecha_emision = $request->fecha_emision;
        $cabecera_salida->motivo = $request->motivo;
        $cabecera_salida->observacion = $request->observacion;
        $cabecera_salida->save();
        $cabecera = CabeceraSalida::orderBy('id', 'desc')->first()->id;
        return $cabecera;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CabeceraSalida  $cabeceraSalida
     * @return \Illuminate\Http\Response
     */
    public function show(CabeceraSalida $cabeceraSalida)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CabeceraSalida  $cabeceraSalida
     * @return \Illuminate\Http\Response
     */
    public function edit(CabeceraSalida $cabeceraSalida)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CabeceraSalida  $cabeceraSalida
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CabeceraSalida $cabeceraSalida)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CabeceraSalida  $cabeceraSalida
     * @return \Illuminate\Http\Response
     */
    public function destroy(CabeceraSalida $cabeceraSalida)
    {
        //
    }
}
