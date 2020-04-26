<?php

namespace App\Http\Controllers;

use App\CabeceraCompra;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CabeceraCompraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $cabecera_compra = CabeceraCompra::select('cabecera_compras.id', 'users.usuario as responsable', 'cabecera_compras.fecha', 'cabecera_compras.total')->join('users', 'cabecera_compras.id_user', '=', 'users.id')->orderBy('cabecera_compras.id', 'desc')->get();
        return $cabecera_compra;
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
        if(!$request->ajax()) return redirect('/');
        $cabecera_compra = new CabeceraCompra();
        $cabecera_compra->cod_proveedor = $request->cod_proveedor;
        $cabecera_compra->ruc_proveedor = $request->ruc_proveedor;
        $cabecera_compra->dir_proveedor = $request->dir_proveedor;
        $cabecera_compra->razon = $request->razon;
        $cabecera_compra->id_user = $request->id_user;
        $cabecera_compra->fecha = $request->fecha;
        $cabecera_compra->sub_total = $request->sub_total;
        $cabecera_compra->desc_global = $request->desc_global;
        $cabecera_compra->igv_total = $request->igv_total;
        $cabecera_compra->total = $request->total;
        $cabecera_compra->save();
        $cabecera_compra = CabeceraCompra::orderBy('id', 'desc')->first()->id;
        return $cabecera_compra;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CabeceraCompra  $cabeceraCompra
     * @return \Illuminate\Http\Response
     */
    public function show(CabeceraCompra $cabeceraCompra)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CabeceraCompra  $cabeceraCompra
     * @return \Illuminate\Http\Response
     */
    public function edit(CabeceraCompra $cabeceraCompra)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CabeceraCompra  $cabeceraCompra
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CabeceraCompra $cabeceraCompra)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CabeceraCompra  $cabeceraCompra
     * @return \Illuminate\Http\Response
     */
    public function destroy(CabeceraCompra $cabeceraCompra)
    {
        //
    }
}
