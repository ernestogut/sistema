<?php

namespace App\Http\Controllers;

use App\CabeceraIngreso;
use App\DetalleIngreso;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CabeceraIngresoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $cabecera_ingreso = CabeceraIngreso::select('cabecera_ingresos.id', 'users.usuario as responsable', 'users.id as id_responsable', 'cabecera_ingresos.fecha_emision', 'cabecera_ingresos.motivo', 'cabecera_ingresos.observacion', 'cabecera_ingresos.estado')->join('users','cabecera_ingresos.id_usuario', '=', 'users.id')->where('cabecera_ingresos.estado', '<>', 'anulado')->orderBy('cabecera_ingresos.id', 'desc')->get();


        //$cabecera_ingreso = CabeceraIngreso::select('cabecera_ingresos.id as num_documento', 'almacens.')
        return $cabecera_ingreso;
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
        $cabecera_ingreso = new CabeceraIngreso();
        $cabecera_ingreso->id_usuario = $request->id_usuario;
        $cabecera_ingreso->fecha_emision = $request->fecha_emision;
        $cabecera_ingreso->motivo = $request->motivo;
        $cabecera_ingreso->observacion = $request->observacion;
        $cabecera_ingreso->estado = 'pendiente';
        $cabecera_ingreso->save();
        $cabecera = CabeceraIngreso::orderBy('id', 'desc')->first()->id;
        return $cabecera;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CabeceraIngreso  $cabeceraIngreso
     * @return \Illuminate\Http\Response
     */
    public function show(CabeceraIngreso $cabeceraIngreso)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CabeceraIngreso  $cabeceraIngreso
     * @return \Illuminate\Http\Response
     */
    public function edit(CabeceraIngreso $cabeceraIngreso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CabeceraIngreso  $cabeceraIngreso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cabecera_ingreso = CabeceraIngreso::find($id);
        //$cabecera_ingreso->id_usuario = $request->id_usuario;
        //$cabecera_ingreso->fecha_emision = $request->fecha_emision;
        $cabecera_ingreso->motivo = $request->motivo;
        $cabecera_ingreso->observacion = $request->observacion;
        $cabecera_ingreso->estado = 'pendiente';
        $cabecera_ingreso->save();
        //$cabecera = CabeceraIngreso::orderBy('id', 'desc')->first()->id;
    }

    public function anularIngreso($id){
        $cabecera_ingreso = CabeceraIngreso::find($id);
        $cabecera_ingreso->estado = 'anulado';
        $cabecera_ingreso->save();

        $detalle_ingreso = DetalleIngreso::select('codigo_padre', 'codigo_producto', 'descripcion_producto', 'cantidades')->where('id_cabecera_ingreso', '=', $id)->get();
        foreach($detalle_ingreso as $value){
                    
            $value->cantidades = json_decode($value->cantidades, true);
            foreach ($value['cantidades'] as $llave => $valor){
                DB::connection("mysql")->statement("call disminuirInventarioAlm(?,?,?)",[$value['codigo_producto'],$valor['cantidad'],$valor['id_almacen']]);
                            $suma_total = DB::table('inventarios')->where('id_producto', '=', $value['codigo_producto'])->sum('cantidad');
                            DB::connection("speed")->statement("call actualizarInventario(?,?)",[$value['codigo_producto'],$suma_total]); 
                            if($value['codigo_padre'] != 0){
                                    DB::connection("mysql")->statement("call disminuirInventarioAlm(?,?,?)",[$value['codigo_padre'],$valor['cantidad'],$valor['id_almacen']]);
    
                                //DB::connection("mysql")->statement("call aumentarInventarioAlm(?,?,?)",[$value['codigo_padre'],$value['cantidad'],$value['almacen']]);
                            
                                $suma_total = DB::table('inventarios')->where('id_producto', '=', $value['codigo_padre'])->sum('cantidad');
                
                                DB::connection("speed")->statement("call actualizarInventario(?,?)",[$value['codigo_padre'],$suma_total]);
                            }    
                        
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CabeceraIngreso  $cabeceraIngreso
     * @return \Illuminate\Http\Response
     */
    public function destroy(CabeceraIngreso $cabeceraIngreso)
    {
        //
    }
}
