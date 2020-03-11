<?php

namespace App\Http\Controllers;

use App\DetalleTraslado;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetalleTrasladoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
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
            DB::connection("mysql")->statement("call disminuirInventarioAlm(?,?,?)",[$value['codigo'],$value['cantidad'],$value['almacen_origen']]);
            DB::connection("mysql")->statement("call aumentarInventarioAlm(?,?,?)",[$value['codigo'],$value['cantidad'],$value['almacen_destino']]);
            /*$suma_total = DB::table('inventarios')->where('id_producto', '=', $value['codigo'])->sum('cantidad');
            DB::connection("speed")->statement("call actualizarInventario(?,?)",[$value['codigo'],$suma_total]);   
            /*DB::select("call disminuirInventario(?,?)",[$value['codigo'],$value['cantidad']]);*/
            $detalle_traslado = new DetalleTraslado();
            $detalle_traslado->id_cabecera_traslado = $request->id_cabecera_traslado;
            $detalle_traslado->codigo_producto = $value['codigo'];
            $detalle_traslado->descripcion_producto = $value['producto'];
            //$detalle_traslado->precio_producto = $value['precio'];
            $detalle_traslado->cantidad_producto = $value['cantidad'];
            //$detalle_traslado->descuento_producto = $value['descuento'];
            //$detalle_traslado->total_producto = $value['total'];
            $detalle_traslado->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DetalleTraslado  $detalleTraslado
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        /*$detalle_traslado = DetalleTraslado::select('detalle_traslados.id as num_doc_detalle', 'cabecera_traslados.id as num_doc_cabecera', 'detalle_traslados.codigo_producto', 'detalle_traslados.descripcion_producto', 'detalle_traslados.cantidad_producto')->join('cabecera_traslados', 'detalle_traslados.id_cabecera_traslado', '=', $id)->get();*/
        $detalle_traslado = DetalleTraslado::select('id as num_doc_detalle', 'id_cabecera_traslado as num_doc_cabecera')->where('id_cabecera_traslado', '=', $id)->get();


        $detalle_productos = DetalleTraslado::select('codigo_producto','descripcion_producto','cantidad_producto')->where('id_cabecera_traslado', '=', $id)->get();

        
        //dd($general);
        return response([$detalle_traslado[0], $detalle_productos]);
        /*$detalle_traslado = DetalleTraslado::select('detalle_traslados.id', 'cabecera_traslados.id_usuario','detalle_traslados.codigo_producto','detalle_traslados.descripcion_producto','detalle_traslados.cantidad_producto')->join('cabecera_traslados','detalle_traslados.id_cabecera_traslado', '=', $id)->get();*/
    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DetalleTraslado  $detalleTraslado
     * @return \Illuminate\Http\Response
     */
    public function edit(DetalleTraslado $detalleTraslado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DetalleTraslado  $detalleTraslado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DetalleTraslado $detalleTraslado)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DetalleTraslado  $detalleTraslado
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetalleTraslado $detalleTraslado)
    {
        //
    }
}
