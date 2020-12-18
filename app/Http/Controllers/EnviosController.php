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
        $ranking_departamento = Envios::select(DB::raw('envios.id_factura, envios.departamento, envios.provincia, envios.distrito, COUNT(*) as conteo, sum(c_facts.total) as suma_total, sum(c_facts.total) / COUNT(*) as promedio'))->join('c_facts', 'envios.id_factura', 'c_facts.id')->where(DB::raw('DATE(envios.created_at)'), $fecha)->groupBy('envios.departamento')->orderBy(DB::raw('COUNT(*)'), 'DESC')->get();
        return $ranking_departamento;
    }
    public function rankingDepartamentosRango($fecha_inicio, $fecha_fin)
    {
        $ranking_departamento = Envios::select(DB::raw('envios.id_factura, envios.departamento, envios.provincia, envios.distrito, COUNT(*) as conteo, sum(c_facts.total) as suma_total, sum(c_facts.total) / COUNT(*) as promedio'))->join('c_facts', 'envios.id_factura', 'c_facts.id')->whereBetween(DB::raw('DATE(envios.created_at)'), array($fecha_inicio, $fecha_fin))->groupBy('envios.departamento')->orderBy(DB::raw('COUNT(*)'), 'DESC')->get();
        return $ranking_departamento;
    }
    public function rankingProvinciasFecha($fecha)
    {
        $ranking_provincia = Envios::select(DB::raw('envios.id_factura, envios.departamento, envios.provincia, envios.distrito, COUNT(*) as conteo, sum(c_facts.total) as suma_total, sum(c_facts.total) / COUNT(*) as promedio'))->join('c_facts', 'envios.id_factura', 'c_facts.id')->where(DB::raw('DATE(envios.created_at)'), $fecha)->groupBy('envios.provincia')->orderBy(DB::raw('COUNT(*)'), 'DESC')->get();
        return $ranking_provincia;
    }
    public function rankingProvinciasRango($fecha_inicio, $fecha_fin)
    {
        $ranking_provincia = Envios::select(DB::raw('envios.id_factura, envios.departamento, envios.provincia, envios.distrito, COUNT(*) as conteo, sum(c_facts.total) as suma_total, sum(c_facts.total) / COUNT(*) as promedio'))->join('c_facts', 'envios.id_factura', 'c_facts.id')->whereBetween(DB::raw('DATE(envios.created_at)'), array($fecha_inicio, $fecha_fin))->groupBy('envios.provincia')->orderBy(DB::raw('COUNT(*)'), 'DESC')->get();
        return $ranking_provincia;
    }
    public function rankingDistritosFecha($fecha)
    {
        $ranking_distrito = Envios::select(DB::raw('envios.id_factura, envios.departamento, envios.provincia, envios.distrito, COUNT(*) as conteo, sum(c_facts.total) as suma_total, sum(c_facts.total) / COUNT(*) as promedio'))->join('c_facts', 'envios.id_factura', 'c_facts.id')->where(DB::raw('DATE(envios.created_at)'), $fecha)->groupBy('envios.distrito')->orderBy(DB::raw('COUNT(*)'), 'DESC')->get();
        return $ranking_distrito;
    }
    public function rankingDistritosRango($fecha_inicio, $fecha_fin)
    {
        $ranking_distrito = Envios::select(DB::raw('envios.id_factura, envios.departamento, envios.provincia, envios.distrito, COUNT(*) as conteo, sum(c_facts.total) as suma_total, sum(c_facts.total) / COUNT(*) as promedio'))->join('c_facts', 'envios.id_factura', 'c_facts.id')->whereBetween(DB::raw('DATE(envios.created_at)'), array($fecha_inicio, $fecha_fin))->groupBy('envios.distrito')->orderBy(DB::raw('COUNT(*)'), 'DESC')->get();
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
