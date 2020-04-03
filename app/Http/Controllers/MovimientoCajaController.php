<?php

namespace App\Http\Controllers;

use App\MovimientoCaja;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovimientoCajaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $movimiento_caja = MovimientoCaja::select('users.usuario as responsable', 'almacens.descripcion as tienda', 'movimiento_cajas.fecha', 'movimiento_cajas.monto', 'movimiento_cajas.tipo_movimiento', 'movimiento_cajas.observacion')->join('users', 'movimiento_cajas.id_usuario', '=', 'users.id')->join('almacens', 'movimiento_cajas.id_almacen', '=', 'almacens.id')->get();
        return $movimiento_caja;
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
        if($request->tipo_movimiento == 'egreso'){
            DB::connection("mysql")->statement("call generarMovimientoEgreso(?,?,?)",[$request->monto, $request->fecha, $request->id_almacen]);
        }else if($request->tipo_movimiento == 'ingreso'){
            DB::connection("mysql")->statement("call generarMovimientoIngreso(?,?,?)",[$request->monto, $request->fecha, $request->id_almacen]);
        }
        $movimiento_caja = new MovimientoCaja();
        $movimiento_caja->id_usuario = $request->id_usuario;
        $movimiento_caja->id_almacen = $request->id_almacen;
        $movimiento_caja->fecha = $request->fecha;
        $movimiento_caja->observacion = $request->observacion;
        $movimiento_caja->monto = $request->monto;
        $movimiento_caja->tipo_movimiento = $request->tipo_movimiento;
        $movimiento_caja->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MovimientoCaja  $movimientoCaja
     * @return \Illuminate\Http\Response
     */
    public function show($almacen)
    {
        $movimiento_caja = MovimientoCaja::select('users.usuario as responsable', 'almacens.descripcion as tienda', 'movimiento_cajas.fecha', 'movimiento_cajas.monto', 'movimiento_cajas.tipo_movimiento', 'movimiento_cajas.observacion')->join('users', 'movimiento_cajas.id_usuario', '=', 'users.id')->join('almacens', 'movimiento_cajas.id_almacen', '=', 'almacens.id')->where('movimiento_cajas.id_almacen', '=', $almacen)->where('movimiento_cajas.fecha', '=', date('Y-m-d'))->get();
        return $movimiento_caja;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MovimientoCaja  $movimientoCaja
     * @return \Illuminate\Http\Response
     */
    public function edit(MovimientoCaja $movimientoCaja)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MovimientoCaja  $movimientoCaja
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MovimientoCaja $movimientoCaja)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MovimientoCaja  $movimientoCaja
     * @return \Illuminate\Http\Response
     */
    public function destroy(MovimientoCaja $movimientoCaja)
    {
        //
    }
}
