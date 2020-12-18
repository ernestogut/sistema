<?php

namespace App\Http\Controllers;

use App\DetalleSalida;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetalleSalidaController extends Controller
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
                /*$producto_en_almacen = DB::table('inventarios')->where('id_producto', $value['codigo'])->where('id_almacen', $value['almacen'])->get();*/
                foreach ($value['cantidades'] as $llave => $valor){
                        DB::connection("mysql")->statement("call disminuirInventarioAlm(?,?,?)",[$value['codigo'],$valor['cantidad'],$valor['id_almacen']]);
                        $suma_total = DB::table('inventarios')->where('id_producto', '=', $value['codigo'])->sum('cantidad');
                        DB::connection("speed")->statement("call actualizarInventario(?,?)",[$value['codigo'],$suma_total]); 
                        if($value['codigo_padre'] != 0){
                                DB::connection("mysql")->statement("call disminuirInventarioAlm(?,?,?)",[$value['codigo_padre'],$valor['cantidad'],$valor['id_almacen']]);
                            $suma_total = DB::table('inventarios')->where('id_producto', '=', $value['codigo_padre'])->sum('cantidad');
            
                            DB::connection("speed")->statement("call actualizarInventario(?,?)",[$value['codigo_padre'],$suma_total]);
                        }    
                        //$en_almacen = true;
                    
                }
                //# code...
                //var_dump($key);
                //var_dump($value);
                $detalle_salida = new DetalleSalida();
                $detalle_salida->id_cabecera_salida = $request->id_cabecera_salida;
                $detalle_salida->codigo_producto = $value['codigo'];
                $detalle_salida->descripcion_producto = $value['producto'];
                $detalle_salida->cantidades = json_encode($value['cantidades']);
                $detalle_salida->save();    
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DetalleSalida  $detalleSalida
     * @return \Illuminate\Http\Response
     */
    public function show($id_cabecera_salida)
    {
        $detalle_salida = DetalleSalida::select('codigo_producto', 'descripcion_producto', 'cantidades')->where('id_cabecera_salida', '=', $id_cabecera_salida)->get();
        foreach($detalle_salida as $valor){
            $valor->cantidades = json_decode($valor->cantidades, true);
        }
        return($detalle_salida);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DetalleSalida  $detalleSalida
     * @return \Illuminate\Http\Response
     */
    public function edit(DetalleSalida $detalleSalida)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DetalleSalida  $detalleSalida
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DetalleSalida $detalleSalida)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DetalleSalida  $detalleSalida
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetalleSalida $detalleSalida)
    {
        //
    }
}
