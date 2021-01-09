<?php

namespace App\Http\Controllers;

use App\TipoPago;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TipoPagoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipo_pago = TipoPago::all();
        return $tipo_pago;
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
        $tipo_pago = new TipoPago();
        $tipo_pago->nombre = $request->nombre;
        $tipo_pago->descripcion = $request->descripcion;
        $tipo_pago->metodo = $request->metodo;
        $tipo_pago->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TipoPago  $tipoPago
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tipo_pago = TipoPago::find($id);
        return $tipo_pago;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TipoPago  $tipoPago
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoPago $tipoPago)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TipoPago  $tipoPago
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tipo_pago = TipoPago::find($id);
        $tipo_pago->nombre = $request->nombre;
        $tipo_pago->descripcion = $request->descripcion;
        $tipo_pago->metodo = $request->metodo;
        $tipo_pago->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TipoPago  $tipoPago
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tipo_pago = TipoPago::find($id);
        $tipo_pago->delete();
    }
}
