<?php

namespace App\Http\Controllers;

use App\ReporteVenta;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
    public function ventasPorFecha($fecha, $almacen)
    {
        if($almacen != 0){
            $ventas = ReporteVenta::select('c_facts.id as num_doc', 'users.usuario', 'c_facts.serie', 'c_facts.folio', 'c_facts.fecha', 'c_facts.tipo_pago', 'c_facts.total')->join('users', 'c_facts.id_user', '=', 'users.id')->where('fecha', '=', $fecha)->where('c_facts.id_almacen', $almacen)->where('estado', 'habilitado')->orderBy('c_facts.id', 'desc')->get();
            return $ventas;
        }else{
            $ventas = ReporteVenta::select('c_facts.id as num_doc', 'users.usuario', 'c_facts.serie', 'c_facts.folio', 'c_facts.fecha', 'c_facts.tipo_pago', 'c_facts.total')->join('users', 'c_facts.id_user', '=', 'users.id')->where('fecha', '=', $fecha)->where('estado', 'habilitado')->orderBy('c_facts.id', 'desc')->get();
            return $ventas;
        }
        
    }
    public function ventasPorFechaRango($fecha_inicio, $fecha_fin, $almacen)
    {
        if($almacen != 0){
            $ventas = ReporteVenta::select('c_facts.id as num_doc', 'users.usuario', 'c_facts.serie', 'c_facts.folio', 'c_facts.fecha', 'c_facts.tipo_pago', 'c_facts.total')->join('users', 'c_facts.id_user', '=', 'users.id')->whereBetween('c_facts.fecha', array($fecha_inicio, $fecha_fin))->where('c_facts.id_almacen', $almacen)->where('estado', 'habilitado')->orderBy('c_facts.id', 'desc')->get();
            return $ventas;
        }else{
            $ventas = ReporteVenta::select('c_facts.id as num_doc', 'users.usuario', 'c_facts.serie', 'c_facts.folio', 'c_facts.fecha', 'c_facts.tipo_pago', 'c_facts.total')->join('users', 'c_facts.id_user', '=', 'users.id')->whereBetween('c_facts.fecha', array($fecha_inicio, $fecha_fin))->where('estado', 'habilitado')->orderBy('c_facts.id', 'desc')->get();
            return $ventas;
        }
    }
    public function obtenerVentasDiariasPorAlmacen($almacen){
        $ventas_diarias = ReporteVenta::select(DB::raw("SUM(c_facts.total) as total, almacens.descripcion as almacen"))->join('almacens', 'c_facts.id_almacen', 'almacens.id')->where('c_facts.fecha', date("Y-m-d"))->where('id_almacen', $almacen)->where('estado', 'habilitado')->get();
        return $ventas_diarias;
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
        DB::statement("SET lc_time_names = 'es_ES'");
        $ventas = ReporteVenta::select(DB::raw('YEAR(fecha) as year, MONTHNAME(fecha) month, SUM(total) as total, count(*) as ordenes'))->groupBy(DB::raw('YEAR(fecha), MONTHNAME(fecha)'))->orderBy(DB::raw('MONTH(fecha)'))->where('id_almacen', '=', $almacen)->where('estado', 'habilitado')->get();
        return $ventas;
    }

    public function mostrarVentasPorSemana($almacen)
    {
        $ventas = ReporteVenta::select(DB::raw("(ELT(WEEKDAY(fecha) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS dia_semana, SUM(total) as total, count(*) as ordenes"))->where(DB::raw('YEARWEEK(fecha)'),'=',DB::raw('YEARWEEK(NOW())'))->groupBy(DB::raw('dia_semana'))->orderBy(DB::raw('fecha, "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado", "Domingo"'))->where('id_almacen', $almacen)->where('estado', 'habilitado')->get();
        return $ventas;

       
        //return $ventas;
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
