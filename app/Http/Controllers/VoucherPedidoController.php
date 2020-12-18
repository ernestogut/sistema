<?php

namespace App\Http\Controllers;

use App\VoucherPedido;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VoucherPedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\VoucherPedido  $voucherPedido
     * @return \Illuminate\Http\Response
     */
    public function show(VoucherPedido $voucherPedido)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\VoucherPedido  $voucherPedido
     * @return \Illuminate\Http\Response
     */
    public function edit(VoucherPedido $voucherPedido)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\VoucherPedido  $voucherPedido
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VoucherPedido $voucherPedido)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\VoucherPedido  $voucherPedido
     * @return \Illuminate\Http\Response
     */
    public function destroy($nombre)
    {
        $voucher = VoucherPedido::where('voucher', $nombre)->first();
        $voucher->delete();
    }
}
