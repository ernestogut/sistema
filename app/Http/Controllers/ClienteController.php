<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClienteController extends Controller
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
            /*$cliente = Cliente::All();
            return $cliente;*/
            $cliente = Cliente::select('clientes.id', 'clientes.codigo','clientes.razon','clientes.direccion','tipo_documentos.tipo_doc as tipo_documento','clientes.num_documento')->join('tipo_documentos','clientes.id_tipo_doc', '=', 'tipo_documentos.id')->get();
            /*$cliente = Cliente::select('codigo', 'razon', 'direccion', 'ruc')->where('codigo', '=', $codigo)->get();*/
            return $cliente;
        }
    }
    public function listarClientesModal($documento)
    {
      
            /*$cliente = Cliente::All();
            return $cliente;*/
            $cliente = Cliente::select('codigo', 'razon', 'direccion', 'num_documento')->where('id_tipo_doc', '=', $documento)->get();
            /*$cliente = Cliente::select('codigo', 'razon', 'direccion', 'ruc')->where('codigo', '=', $codigo)->get();*/
            return $cliente;
    
    }
    public function buscarCliente($codigo)
    {
        $cliente = Cliente::select('codigo', 'razon', 'direccion', 'num_documento')->where('codigo', '=', $codigo)->get();
        return $cliente;
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

        if(!$request->ajax()){
            return redirect('/');
        }else{
            $cliente = new Cliente();
            $cliente->id_tipo_doc = $request->id_tipo_doc;
            $cliente->codigo = $request->codigo;
            $cliente->razon = $request->razon;
            $cliente->direccion = $request->direccion;
            $cliente->num_documento = $request->num_documento;
            $cliente->save();
            return $cliente;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        /*$cliente = Cliente::select('clientes.codigo','clientes.razon','clientes.direccion','tipo_documentos.tipo_doc as tipo_documento','clientes.num_documento')->join('tipo_documentos','clientes.id_tipo_doc', '=', 'tipo_documentos.id')->get();*/
        
        $cliente = Cliente::find($id);
        return $cliente;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!$request->ajax()){
            return redirect('/');
        }else{
            $cliente = Cliente::find($id);
            $cliente->id_tipo_doc = $request->id_tipo_doc;
            $cliente->codigo = $request->codigo;
            $cliente->razon = $request->razon;
            $cliente->direccion = $request->direccion;
            $cliente->num_documento = $request->num_documento;
            $cliente->save();
            return $cliente;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cliente = Cliente::find($id);
        $cliente->delete();
    }
}
