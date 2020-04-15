<?php

namespace App\Http\Controllers;

use App\PedidosSpeed;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PedidosSpeedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ordenes = PedidosSpeed::all();
        return $ordenes;
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PedidosSpeed  $pedidosSpeed
     * @return \Illuminate\Http\Response
     */
    public function show(PedidosSpeed $pedidosSpeed)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PedidosSpeed  $pedidosSpeed
     * @return \Illuminate\Http\Response
     */
    public function edit(PedidosSpeed $pedidosSpeed)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PedidosSpeed  $pedidosSpeed
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PedidosSpeed $pedidosSpeed)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PedidosSpeed  $pedidosSpeed
     * @return \Illuminate\Http\Response
     */
    public function destroy(PedidosSpeed $pedidosSpeed)
    {
        //
    }
}
