<?php

namespace App\Http\Controllers;

use App\DetalleIngreso;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetalleIngresoController extends Controller
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
        if(!$request->ajax()) return redirect('/');

        //dd($request->id_cabecera);
        $detalle = $request->ventas;


        foreach ($detalle as $key => $value) {
            //# code...
            //var_dump($key);
            //var_dump($value);
            foreach ($value['cantidades'] as $llave => $valor){
                DB::connection("mysql")->statement("call aumentarInventarioAlm(?,?,?)",[$value['codigo'],$valor['cantidad'],$valor['id_almacen']]);
            }
            $suma_total = DB::table('inventarios')->where('id_producto', '=', $value['codigo'])->sum('cantidad');
            DB::connection("speed")->statement("call actualizarInventario(?,?)",[$value['codigo'],$suma_total]); 
            if($value['codigo_padre'] != 0){
                foreach ($value['cantidades'] as $clave => $valo){
                    DB::connection("mysql")->statement("call aumentarInventarioAlm(?,?,?)",[$value['codigo_padre'],$valo['cantidad'],$valo['id_almacen']]);
                }
                //DB::connection("mysql")->statement("call aumentarInventarioAlm(?,?,?)",[$value['codigo_padre'],$value['cantidad'],$value['almacen']]);
            
                $suma_total = DB::table('inventarios')->where('id_producto', '=', $value['codigo_padre'])->sum('cantidad');

                DB::connection("speed")->statement("call actualizarInventario(?,?)",[$value['codigo_padre'],$suma_total]);
            }    
            $detalle_ingreso = new DetalleIngreso();
            $detalle_ingreso->id_cabecera_ingreso = $request->id_cabecera_ingreso;
            $detalle_ingreso->codigo_producto = $value['codigo'];
            $detalle_ingreso->descripcion_producto = $value['producto'];
            $detalle_ingreso->cantidades = json_encode($value['cantidades']);
            $detalle_ingreso->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DetalleIngreso  $detalleIngreso
     * @return \Illuminate\Http\Response
     */
    public function show($id_cabecera_ingreso)
    {
        $detalle_ingreso = DetalleIngreso::select('codigo_producto', 'descripcion_producto', 'cantidades')->where('id_cabecera_ingreso', '=', $id_cabecera_ingreso)->get();
        foreach($detalle_ingreso as $valor){
            $valor->cantidades = json_decode($valor->cantidades, true);
        }
        return($detalle_ingreso);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DetalleIngreso  $detalleIngreso
     * @return \Illuminate\Http\Response
     */
    public function edit(DetalleIngreso $detalleIngreso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DetalleIngreso  $detalleIngreso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DetalleIngreso $detalleIngreso)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DetalleIngreso  $detalleIngreso
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetalleIngreso $detalleIngreso)
    {
        //
    }
}
