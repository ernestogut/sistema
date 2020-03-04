<?php

namespace App\Http\Controllers;

use App\CabeceraIngreso;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CabeceraIngresoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $cabecera_ingreso = CabeceraIngreso::select('cabecera_ingresos.id as num_documento', 'almacens.descripcion as almacen', 'users.usuario as responsable','cabecera_ingresos.fecha_emision', 'cabecera_ingresos.motivo',  'cabecera_ingresos.observacion')->join('almacens','cabecera_ingresos.id_almacen', '=', 'almacens.id')->join('users','cabecera_ingresos.id_usuario', '=', 'users.id')->get();


        //$cabecera_ingreso = CabeceraIngreso::select('cabecera_ingresos.id as num_documento', 'almacens.')
        return $cabecera_ingreso;
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
        $cabecera_ingreso = new CabeceraIngreso();
        $cabecera_ingreso->id_almacen = $request->id_almacen;
        $cabecera_ingreso->id_usuario = $request->id_usuario;
        $cabecera_ingreso->fecha_emision = $request->fecha_emision;
        $cabecera_ingreso->motivo = $request->motivo;
        $cabecera_ingreso->observacion = $request->observacion;
        $cabecera_ingreso->save();
        $cabecera = CabeceraIngreso::orderBy('id', 'desc')->first()->id;
        return $cabecera;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CabeceraIngreso  $cabeceraIngreso
     * @return \Illuminate\Http\Response
     */
    public function show(CabeceraIngreso $cabeceraIngreso)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CabeceraIngreso  $cabeceraIngreso
     * @return \Illuminate\Http\Response
     */
    public function edit(CabeceraIngreso $cabeceraIngreso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CabeceraIngreso  $cabeceraIngreso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CabeceraIngreso $cabeceraIngreso)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CabeceraIngreso  $cabeceraIngreso
     * @return \Illuminate\Http\Response
     */
    public function destroy(CabeceraIngreso $cabeceraIngreso)
    {
        //
    }
}
