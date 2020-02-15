<?php

namespace App\Http\Controllers;

use App\Comprobante;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ComprobanteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if(!$request->ajax()){
            return redirect('/');
        }else{
            $comprobante = Comprobante::select('tipo_comprobantes.id as id','tipo_comprobantes.nombre','comprobantes.serie','comprobantes.numero','comprobantes.año')->join('tipo_comprobantes','comprobantes.id_tipo_comprobante', '=', 'tipo_comprobantes.id')->orderBy('comprobantes.id', 'desc')->get();
            return $comprobante;
        } 

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
        $comprobante = new Comprobante();
        $comprobante->id_tipo_comprobante = $request->id_tipo_comprobante;
        $comprobante->serie = $request->serie;
        $comprobante->numero = $request->numero;
        $comprobante->año = $request->año;
        $comprobante->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comprobante  $comprobante
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $comprobante = Comprobante::select('serie', 'numero', 'año')->where('id_tipo_comprobante', '=', $id)->get();
        return $comprobante;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comprobante  $comprobante
     * @return \Illuminate\Http\Response
     */
    public function edit(Comprobante $comprobante)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comprobante  $comprobante
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $comprobante = Comprobante::find($id);
        $comprobante->id_tipo_comprobante = $request->id_tipo_comprobante;
        $comprobante->serie = $request->serie;
        $comprobante->numero = $request->numero;
        $comprobante->año = $request->año;
        $comprobante->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comprobante  $comprobante
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comprobante $comprobante)
    {
        //
    }
}
