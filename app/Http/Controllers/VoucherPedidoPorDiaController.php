<?php

namespace App\Http\Controllers;

use App\VoucherPedidoPorDia;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Image;

class VoucherPedidoPorDiaController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\VoucherPedidoPorDia  $voucherPedidoPorDia
     * @return \Illuminate\Http\Response
     */
    public function show(VoucherPedidoPorDia $voucherPedidoPorDia)
    {
        //
    }

    public function subirVoucherPorDia(Request $request){
        if($request->hasFile('voucher')){
            $voucher = $request->file('voucher');
            $filename = $request->id_dia . '_' . date('Y-m-d') . '.' . $voucher->getClientOriginalExtension();
            Image::make($voucher)->resize(500, 500)->save( public_path('/uploads/vouchers_por_dia/') . $filename);
            $dia = VoucherPedidoPorDia::find($request->id_dia);
            $dia->voucher = $filename;
            $dia->save();
        }
        return $dia;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\VoucherPedidoPorDia  $voucherPedidoPorDia
     * @return \Illuminate\Http\Response
     */
    public function edit(VoucherPedidoPorDia $voucherPedidoPorDia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\VoucherPedidoPorDia  $voucherPedidoPorDia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VoucherPedidoPorDia $voucherPedidoPorDia)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\VoucherPedidoPorDia  $voucherPedidoPorDia
     * @return \Illuminate\Http\Response
     */
    public function destroy(VoucherPedidoPorDia $voucherPedidoPorDia)
    {
        //
    }
}
