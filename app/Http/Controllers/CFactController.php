<?php

namespace App\Http\Controllers;

use App\C_fact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CFactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $cabecera = C_fact::select('id as num_doc', 'razon', 'serie', 'folio', 'fecha', 'total')->orderBy('id', 'desc')->get();
        return $cabecera;
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
        $cabecera = new C_fact();
        DB::select("call insertarCabeceraFact(?,?,?,?,?,?,?,?,?,?,?,?,?,?)",[$request->id_serie, $request->id_tipo_comprobante, $request->cod_cliente,$request->ruc_cliente,$request->dir_cliente,$request->razon,$request->id_user,$request->fecha,$request->tipo_venta,$request->serie,$request->sub_total,$request->desc_global,$request->igv_total,$request->total]);



        /*$cabecera->id_tipo_comprobante = $request->id_tipo_comprobante;
        $cabecera->cod_cliente = $request->cod_cliente;
        $cabecera->ruc_cliente = $request->ruc_cliente;
        $cabecera->dir_cliente = $request->dir_cliente;
        $cabecera->razon = $request->razon;
        $cabecera->id_user = $request->id_user;
        $cabecera->fecha = $request->fecha;
        $cabecera->tipo_venta = $request->tipo_venta;
        $cabecera->serie = $request->serie;
        $cabecera->folio = $request->folio;
        $cabecera->sub_total = $request->sub_total;
        $cabecera->desc_global = $request->desc_global;
        $cabecera->igv_total = $request->igv_total;
        $cabecera->total = $request->total;
        $cabecera->save(); 
        $ultimo_id = $cabecera->id;
        return $ultimo_id;*/
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\C_fact  $c_fact
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $tipo_comprobante)
    {
        if(!$request->ajax()) return redirect('/');
        /*$cabecera = C_fact::find($id);
        return $cabecera;*/
        $cabecera = C_fact::select('id as num_doc', 'razon', 'serie', 'folio', 'fecha', 'total')->where('id_tipo_comprobante', '=', $tipo_comprobante)->orderBy('id', 'desc')->get();
        return $cabecera;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\C_fact  $c_fact
     * @return \Illuminate\Http\Response
     */
    public function edit(C_fact $c_fact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\C_fact  $c_fact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!$request->ajax()) return redirect('/');
        $cabecera = C_fact::find($id);
        $cabecera->id_tipo_comprobante = $request->id_tipo_comprobante;
        $cabecera->cod_cliente = $request->cod_cliente;
        $cabecera->ruc_cliente = $request->ruc_cliente;
        $cabecera->dir_cliente = $request->dir_cliente;
        $cabecera->razon = $request->razon;
        $cabecera->id_users = $request->id_users;
        $cabecera->fecha = $request->fecha;
        $cabecera->tipo_venta = $request->tipo_venta;
        $cabecera->serie = $request->serie;
        $cabecera->folio = $request->folio;
        $cabecera->sub_total = $request->sub_total;
        $cabecera->desc_global = $request->desc_global;
        $cabecera->igv_total = $request->igv_total;
        $cabecera->total = $request->total;
        $cabecera->save();
        return $cabecera;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\C_fact  $c_fact
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cabecera = C_fact::find($id);
        $cabecera->delete();
    }
}
