<?php

namespace App\Http\Controllers;

use App\CierreCaja;
use App\User;
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

        $mensaje = "Existe una caja abierta para esta tienda!";
        $tipo = "danger";
        $arrayUsuarios = User::select('id', 'usuario', 'id_almacen')->get();
        $controlador = false;
        foreach($arrayUsuarios as $objUsuario){
            if($objUsuario->id_almacen == $request->id_almacen){
                $controlador = true;
            }
        }
        if($controlador){
            $tipo = "danger";
           return  back()->withErrors(['error' => 'Existe una caja abierta para esta tienda!']);
            //return redirect()->back()->with('alert',);
        }else{
            $usuario = User::find(auth()->user()->id);
            $usuario->id_almacen = $request->id_almacen;
            $usuario->save();
            $usuario = auth()->user();
            $cierre_caja = new CierreCaja();
            $cierre_caja->id_usuario = $usuario->id;
            $cierre_caja->id_almacen = $request->id_almacen;
            $cierre_caja->fecha = date("Y-m-d");
            $cierre_caja->saldo_inicial = $request->saldo_inicial;
            $cierre_caja->ventas_diarias = 0;
            $cierre_caja->ingresos = 0;
            $cierre_caja->egresos = 0;
            $cierre_caja->saldo_final = $request->saldo_inicial;
            $cierre_caja->estado = 'abierto';
            $cierre_caja->save();
            return redirect()->route('principal');
        }
    /*
        


        
        */
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
        //dd($request->id_almacen);
        $user = User::find(auth()->user()->id);
        $user->id_almacen = $request->id_almacen;
        $cierre_caja->estado = $request->estado;
        $user->save();
        $cierre_caja->save();
    }
    public function consultarCajaSeleccionada($id)
    {
        $cierre_caja = CierreCaja::find($id);
        return $cierre_caja;
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
