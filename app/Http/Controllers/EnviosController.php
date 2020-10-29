<?php

namespace App\Http\Controllers;

use App\Envios;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnviosController extends Controller
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
     * @param  \App\Envios  $envios
     * @return \Illuminate\Http\Response
     */
    public function show(Envios $envios)
    {
        //
    }
    public function rankingDepartamentosFecha($fecha)
    {
        $ranking_departamento = Envios::select(DB::raw('id_factura, departamento, provincia, distrito, COUNT(*) as conteo'))->where(DB::raw('DATE(created_at)'), $fecha)->groupBy('departamento')->orderBy(DB::raw('COUNT(*)'), 'DESC')->get();
        return $ranking_departamento;
    }
    public function rankingDepartamentosRango($fecha_inicio, $fecha_fin)
    {
        $ranking_departamento = Envios::select(DB::raw('id_factura, departamento, provincia, distrito, COUNT(*) as conteo'))->whereBetween(DB::raw('DATE(created_at)'), array($fecha_inicio, $fecha_fin))->groupBy('departamento')->orderBy(DB::raw('COUNT(*)'), 'DESC')->get();
        return $ranking_departamento;
    }
    public function rankingProvinciasFecha($fecha)
    {
        $ranking_provincia = Envios::select(DB::raw('id_factura, departamento, provincia, distrito, COUNT(*) as conteo'))->where(DB::raw('DATE(created_at)'), $fecha)->groupBy('provincia')->orderBy(DB::raw('COUNT(*)'), 'DESC')->get();
        return $ranking_provincia;
    }
    public function rankingProvinciasRango($fecha_inicio, $fecha_fin)
    {
        $ranking_provincia = Envios::select(DB::raw('id_factura, departamento, provincia, distrito, COUNT(*) as conteo'))->whereBetween(DB::raw('DATE(created_at)'), array($fecha_inicio, $fecha_fin))->groupBy('provincia')->orderBy(DB::raw('COUNT(*)'), 'DESC')->get();
        return $ranking_provincia;
    }
    public function rankingDistritosFecha($fecha)
    {
        $ranking_distrito = Envios::select(DB::raw('id_factura, departamento, provincia, distrito, COUNT(*) as conteo'))->where(DB::raw('DATE(created_at)'), $fecha)->groupBy('distrito')->orderBy(DB::raw('COUNT(*)'), 'DESC')->get();
        return $ranking_distrito;
    }
    public function rankingDistritosRango($fecha_inicio, $fecha_fin)
    {
        $ranking_distrito = Envios::select(DB::raw('id_factura, departamento, provincia, distrito, COUNT(*) as conteo'))->whereBetween(DB::raw('DATE(created_at)'), array($fecha_inicio, $fecha_fin))->groupBy('distrito')->orderBy(DB::raw('COUNT(*)'), 'DESC')->get();
        return $ranking_distrito;
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Envios  $envios
     * @return \Illuminate\Http\Response
     */
    public function edit(Envios $envios)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Envios  $envios
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Envios $envios)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Envios  $envios
     * @return \Illuminate\Http\Response
     */
    public function destroy(Envios $envios)
    {
        //
    }
}
