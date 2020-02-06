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
            $cliente = Cliente::All();
            return $cliente;
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

        if(!$request->ajax()){
            return redirect('/');
        }else{
            $cliente = new Cliente();
            $cliente->codigo = $request->codigo;
            $cliente->razon = $request->razon;
            $cliente->direccion = $request->direccion;
            $cliente->ruc = $request->ruc;
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
    public function show(String $codigo)
    {
        $cliente = Cliente::select('codigo', 'razon', 'direccion', 'ruc')->where('codigo', '=', $codigo)->get();
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
            $cliente->codigo = $request->codigo;
            $cliente->razon = $request->razon;
            $cliente->direccion = $request->direccion;
            $cliente->ruc = $request->ruc;
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
        $cliente.delete();
    }
}
