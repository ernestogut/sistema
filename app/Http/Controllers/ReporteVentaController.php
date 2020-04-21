<?php

namespace App\Http\Controllers;

use App\ReporteVenta;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteVentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ventas = ReporteVenta::select(DB::raw('YEAR(fecha) as year, MONTHNAME(fecha) month, SUM(total) as subtotal, count(*) as orders'))->groupBy(DB::raw('YEAR(fecha), MONTHNAME(fecha)'))->orderBy(DB::raw('MONTH(fecha)'))->where('id_almacen', '=', 2)->get();
        return $ventas;
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
     * @param  \App\ReporteVenta  $reporteVenta
     * @return \Illuminate\Http\Response
     */
    public function show($almacen)
    {
        //DB::statement("SET lc_time_names = 'es_ES'");
        $ventas = ReporteVenta::select(DB::raw('YEAR(fecha) as year, MONTHNAME(fecha) month, SUM(total) as total, count(*) as ordenes'))->groupBy(DB::raw('YEAR(fecha), MONTHNAME(fecha)'))->orderBy(DB::raw('MONTH(fecha)'))->where('id_almacen', '=', $almacen)->get();
        return $ventas;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ReporteVenta  $reporteVenta
     * @return \Illuminate\Http\Response
     */
    public function edit(ReporteVenta $reporteVenta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ReporteVenta  $reporteVenta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReporteVenta $reporteVenta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ReporteVenta  $reporteVenta
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReporteVenta $reporteVenta)
    {
        //
    }
}
