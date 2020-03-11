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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CierreCaja  $cierreCaja
     * @return \Illuminate\Http\Response
     */
    public function show(CierreCaja $cierreCaja)
    {
        //
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
