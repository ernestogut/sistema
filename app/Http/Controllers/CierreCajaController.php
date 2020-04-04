<?php

namespace App\Http\Controllers;

use App\CierreCaja;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CierreCajaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cierre_caja = CierreCaja::all();
        return $cierre_caja;
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
        $cierre_caja = new CierreCaja();
        $cierre_caja->id_usuario = $request->id_usuario;
        $cierre_caja->id_almacen = $request->id_almacen;
        $cierre_caja->fecha = $request->fecha;
        $cierre_caja->saldo_inicial = $request->saldo_inicial;
        $cierre_caja->ventas_diarias = $request->ventas_diarias;
        $cierre_caja->ingresos = $request->ingresos;
        $cierre_caja->egresos = $request->egresos;
        $cierre_caja->saldo_final = $request->saldo_final;
        $cierre_caja->estado = $request->estado;
        $cierre_caja->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CierreCaja  $cierreCaja
     * @return \Illuminate\Http\Response
     */
    public function show($codigo)
    {
        $cierre_caja = CierreCaja::select('cierre_cajas.id', 'users.usuario as responsable', 'cierre_cajas.id_almacen', 'fecha', 'cierre_cajas.fecha', 'cierre_cajas.saldo_inicial', 'cierre_cajas.ventas_diarias', 'cierre_cajas.ingresos', 'cierre_cajas.egresos', 'cierre_cajas.saldo_final', 'cierre_cajas.estado')->join('users', 'cierre_cajas.id_usuario', '=', 'users.id')->where('cierre_cajas.id_almacen', '=', $codigo)->orderBy('cierre_cajas.id', 'desc')->take(10)->get();
        return $cierre_caja;
    }
    public function verificarEstadoCaja($codigo)
    {
        $cierre_caja = CierreCaja::select('id_usuario', 'fecha', 'estado')->where('id_almacen', '=', $codigo)->orderBy('id', 'desc')->take(2)->get();
        return $cierre_caja;
    }
    public function montoDiaAnterior($almacen)
    {
        $monto_anterior = CierreCaja::select('saldo_final')->where('id_almacen', '=', $almacen)->orderBy('id', 'desc')->take(1)->get();
        return $monto_anterior;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CierreCaja  $cierreCaja
     * @return \Illuminate\Http\Response
     */
    public function edit(CierreCaja $cierreCaja)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CierreCaja  $cierreCaja
     * @return \Illuminate\Http\Response
     */
    public function cerrarCaja(Request $request, $id)
    {
        $cierre_caja = CierreCaja::find($id);
        $cierre_caja->estado = $request->estado;
        $cierre_caja->save();
    }
    public function update(Request $request, CierreCaja $cierreCaja)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CierreCaja  $cierreCaja
     * @return \Illuminate\Http\Response
     */
    public function destroy(CierreCaja $cierreCaja)
    {
        //
    }
}
