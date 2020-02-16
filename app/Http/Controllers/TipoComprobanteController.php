<?php

namespace App\Http\Controllers;

use App\TipoComprobante;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TipoComprobanteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipo_comprobante = TipoComprobante::all();
        return $tipo_comprobante;
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
        $tipo_comprobante = new TipoComprobante();
        $tipo_comprobante->nombre = $request->nombre;
        $tipo_comprobante->descripcion = $request->descripcion;
        $tipo_comprobante->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TipoComprobante  $tipoComprobante
     * @return \Illuminate\Http\Response
     */
    public function show(TipoComprobante $tipoComprobante)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TipoComprobante  $tipoComprobante
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoComprobante $tipoComprobante)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TipoComprobante  $tipoComprobante
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tipo_comprobante = TipoComprobante::find($id);
        $tipo_comprobante->nombre = $request->nombre;
        $tipo_comprobante->descripcion = $request->descripcion;
        $tipo_comprobante->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TipoComprobante  $tipoComprobante
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipoComprobante $tipoComprobante)
    {
        //
    }
}
