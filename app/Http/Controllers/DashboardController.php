<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $anio=date('Y');
        $ingresos=DB::table('ingresos as i')
        ->select(DB::raw('MONTH(i.fecha_hora) as mes'),
        DB::raw('YEAR(i.fecha_hora) as anio'),
        DB::raw('SUM(i.total) as total'))
        ->whereYear('i.fecha_hora',$anio)
        ->groupBy(DB::raw('MONTH(i.fecha_hora)'),DB::raw('YEAR(i.fecha_hora)'))
        ->get();

        $ventas=DB::table('c_facts as v')
        ->select(DB::raw('MONTH(v.fecha) as mes'),
        DB::raw('YEAR(v.fecha) as anio'),
        DB::raw('SUM(v.total) as total'))
        ->whereYear('v.fecha',$anio)
        ->groupBy(DB::raw('MONTH(v.fecha)'),DB::raw('YEAR(v.fecha)'))
        ->get();

        return ['ingresos'=>$ingresos,'ventas'=>$ventas,'anio'=>$anio];

    }
}
