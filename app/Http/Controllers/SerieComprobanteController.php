<?php

namespace App\Http\Controllers;

use App\SerieComprobante;
use Illuminate\Http\Request;


class SerieComprobanteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $serie_comprobante = SerieComprobante::select('serie', 'numero_inicial', 'numero_actual', 'numero_final', 'anio', 'estado')->get();
        return $serie_comprobante;
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
        $serie_comprobante = new SerieComprobante();
        $serie_comprobante->id_tipo_comprobante = $request->id_tipo_comprobante;
        $serie_comprobante->serie = $request->serie;
        $serie_comprobante->numero_inicial = $request->numero_inicial;
        $serie_comprobante->numero_actual = $request->numero_actual;
        $serie_comprobante->numero_final = $request->numero_final;
        $serie_comprobante->anio = $request->anio;
        $serie_comprobante->estado = $request->estado;
        $serie_comprobante->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SerieComprobante  $serieComprobante
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $serie_comprobante = SerieComprobante::select('id', 'serie', 'numero_inicial', 'numero_actual', 'numero_final', 'anio', 'estado')->where('id_tipo_comprobante', '=', $id)->get();
        return $serie_comprobante;
    }
    public function listarSeries($id)
    {
        $serie_comprobante = SerieComprobante::select('id', 'serie', 'numero_inicial', 'numero_actual', 'numero_final', 'anio', 'estado')->where('id_tipo_comprobante', '=', $id)->where('estado', '=', 1)->get();
        return $serie_comprobante;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SerieComprobante  $serieComprobante
     * @return \Illuminate\Http\Response
     */
    public function edit(SerieComprobante $serieComprobante)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SerieComprobante  $serieComprobante
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $serie_comprobante = SerieComprobante::find($id);
        /*$serie_comprobante->id_tipo_comprobante = $request->id_tipo_comprobante;
        $serie_comprobante->serie = $request->serie;
        $serie_comprobante->numero_inicial = $request->numero_inicial;
        $serie_comprobante->numero_actual = $request->numero_actual;
        $serie_comprobante->numero_final = $request->numero_final;
        $serie_comprobante->anio = $request->anio;*/
        $serie_comprobante->estado = $request->estado;
        $serie_comprobante->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SerieComprobante  $serieComprobante
     * @return \Illuminate\Http\Response
     */
    public function destroy(SerieComprobante $serieComprobante)
    {
        //
    }
}
