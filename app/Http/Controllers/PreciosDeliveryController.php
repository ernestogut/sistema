<?php

namespace App\Http\Controllers;

use App\PreciosDelivery;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PreciosDeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $precios = PreciosDelivery::all();
        return $precios;
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
     * @param  \App\PreciosDelivery  $preciosDelivery
     * @return \Illuminate\Http\Response
     */
    public function show(PreciosDelivery $preciosDelivery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PreciosDelivery  $preciosDelivery
     * @return \Illuminate\Http\Response
     */
    public function edit(PreciosDelivery $preciosDelivery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PreciosDelivery  $preciosDelivery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!$request->ajax()) return redirect('/');
        $precio_delivery = PreciosDelivery::find($id);
        $precio_delivery->costo_envio = $request->costo_envio;
        $precio_delivery->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PreciosDelivery  $preciosDelivery
     * @return \Illuminate\Http\Response
     */
    public function destroy(PreciosDelivery $preciosDelivery)
    {
        //
    }
}
