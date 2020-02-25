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
        $tipo_comprobante = TipoComprobante::select('tipo_comprobantes.id',  'tipo_comprobantes.nombre', 'tipo_documentos.tipo_doc', 'tipo_comprobantes.descripcion')->join('tipo_documentos', 'tipo_comprobantes.id_tipo_doc', '=', 'tipo_documentos.id')->get();
        //$tipo_comprobante = TipoComprobante::all();
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
    public function obtenerComprobantes()
    {
        $tipo_comprobante = TipoComprobante::select('tipo_comprobantes.id', 'tipo_comprobantes.id_tipo_doc', 'tipo_comprobantes.nombre', 'tipo_documentos.tipo_doc')->join('tipo_documentos', 'tipo_comprobantes.id_tipo_doc', '=', 'tipo_documentos.id')->get();
        return $tipo_comprobante;
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
        $tipo_comprobante->id_tipo_doc = $request->id_tipo_doc;
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
    public function show($id)
    {
        $tipo_comprobante = TipoComprobante::find($id);
        return $tipo_comprobante;
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
        $tipo_comprobante->id_tipo_doc = $request->id_tipo_doc;
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
